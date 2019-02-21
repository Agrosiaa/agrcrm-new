<?php
namespace App\Http\Controllers\CustomTraits;

use App\Brand;
use App\BrandCategory;
use App\Category;
use App\CategoryHSNCodeTaxRelation;
use App\Feature;
use App\FeatureOption;
use App\HSNCodes;
use App\HSNCodeTaxRelation;
use App\Http\Requests\Web\Seller\ExcelRequest;
use App\MeasuringUnit;
use App\Product;
use App\ProductCategoryRelation;
use App\ProductFeatureRelation;
use App\ProductImage;
use App\ProductQueryStatus;
use App\Role;
use App\Seller;
use App\User;
use App\SellerAddress;
use App\Tax;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;


trait ExcelTrait{

    use ProductSku;
    public function csvExportView(){
        try{
            $data = [
                'is_active' => 1,
                'category_id' => null,
            ];
            $rootCategories = Category::where($data)->get()->toArray();
            $roleType = $this->userRoleType;
            if($roleType=='superadmin'){
                $sellerRole = Role::where('slug','seller')->first();
                $sellers = User::where(['is_active'=>1,'role_id'=>$sellerRole->id])->get();
                return view('backend.superadmin.products.export-csv')->with(compact('rootCategories','sellers'));
            }elseif($roleType=='seller'){
                return view('backend.seller.product.export-csv')->with(compact('rootCategories'));
            }
        }catch (\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'CSV Export View',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    /*
        - https://github.com/PHPOffice/PHPExcel/wiki/User%20Documentation%20Overview%20and%20Quickstart%20Guide
        - https://github.com/PHPOffice/PHPExcel/blob/develop/Documentation/markdown/Overview/04-Configuration-Settings.md
    */

    public function csvExport(Request $request){
        try{
            $selectedCategory = Category::findBySlugOrFail($request->category);
            $rootCategories = Category::where('category_id',null)->get()->toArray();
            $categoryArray['selectedCategory'] = $selectedCategory->toArray();
            $subCategories = Category::where('category_id',$selectedCategory->id)->get();
            $itemHeads = NULL;
            if(!$subCategories->isEmpty()){
                $categoryArray['subCategories'] = $subCategories->toArray();
                foreach($categoryArray['subCategories'] as $subCategory){
                    if($subCategory['is_item_head']){
                        $itemHeads[] = $subCategory;
                    }else{
                        $subSubCategories = Category::where('category_id',$subCategory['id'])->get();
                        if(!$subSubCategories->isEmpty()){
                            foreach($subSubCategories as $subSubCategory){
                                if($subSubCategory->is_item_head){
                                    $itemHeads[] = $subSubCategory->toArray();
                                }
                            }
                        }
                    }
                }
            }

            if($itemHeads!=NULL){
                if($request->has('seller_id')){
                    $sellerId = $request->seller_id;
                }else{
                    $sellerId = $this->seller->id;
                }
                $company = Seller::where('id',$sellerId)->pluck('company');
                $addressess = SellerAddress::where('seller_id',$sellerId)->select('address_unique_name')->get();
                foreach($addressess as $address){
                    if($address->address_unique_name=='default'){
                        $sellerAddress[] = '';
                    }else{
                        $sellerAddress[] = $address->address_unique_name;
                    }
                }
                $objPHPExcel = new \PHPExcel();
                $objWorkSheet = $objPHPExcel->createSheet();
                /* Units Master List */
                $unitsMaster = MeasuringUnit::all()->toArray();
                foreach($unitsMaster as $unitMaster){
                    if($unitMaster['type']=='Weight Units'){
                        $weightUnits = $unitMaster['values'];
                    }
                    if($unitMaster['type']=='Dimensions Unit'){
                        $dimensionUnits = $unitMaster['values'];
                    }
                    if($unitMaster['type']=='Warranty Unit'){
                        $warrantyUnits = $unitMaster['values'];
                    }
                }
                // Set the active Excel worksheet to sheet 0
                /* 1st Tab */
                $objPHPExcel->getSheet(0)->setTitle('Summary Sheet');
                /* 2nd tab */
                //$objPHPExcel->getSheet(1)->setTitle('Main Index');
                /* Add Root Categories in Excel */
                /*$i = 1;
                $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension('A')->setWidth(50);
                $objWorkSheet->setCellValue('A'.$i, 'Main Categories');
                foreach($rootCategories as $rootCategory){
                    $i++;
                    $objWorkSheet->setCellValue('A'.$i, $rootCategory['name']);
                }*/
                /* Show Selected Category Hierarchy */
                // Create a new worksheet, after the default sheet
                // Add some data to the second sheet, resembling some different data types
                // Rename 2nd Tab
                $objPHPExcel->getSheet(1)->setTitle($categoryArray['selectedCategory']['tab_name']);
                $objPHPExcel->setActiveSheetIndex(1);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $styleArray = array(
                    'font'  => array(
                        'size'  => 14,
                        'color' => array('rgb' => '2619DF'),
                        'underline' => \PHPExcel_Style_Font::UNDERLINE_SINGLE
                    ),
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '92D050')
                    ));
                $i = 1;
                foreach($itemHeads as $itemHead) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $itemHead['name']);
                    //$objPHPExcel->getActiveSheet()->getStyle("A$i")->getFont()->setName('Arial')->setBold(true);
                    $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
                    $objPHPExcel->getActiveSheet()->getStyle("A$i")->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getCell("A$i")->getHyperlink()->setUrl("sheet://'".$itemHead['tab_name']."'!A1");
                    $i++;
                }
                //$objPHPExcel->getActiveSheet()->getStyle("A1:A$i")->getFont()->setSize(40);
                $rows[1] = array("AGROSIAA \rSKU ID","SELLER \rSKU","Product \rName",'GST',
                    'Key Specs 1','Key Specs 2','Key Specs 3',"Search \rKeywords",'Weight',"Final Weight \rMeasuring \rUnit",
                    "Packing \rDimensions","Packaging \rDimensions \rMeasuring \rUnit","Final \rWeight \rOf \rPacked \rMaterial",
                    "Weight \rMeasuring \rUnit","Product \rPick Up \rAddress","Product \rDescription",
                    'Brand',"Model \rName","Other \rFeatures \rAnd \rApplications","Sales \rPackage \rOr \rAccessories",
                    "Domestic \rWarranty","Domestic \rWarranty \rMeasuring \rUnit","Warranty \rSummary","Warranty \rService \rType",
                    "Warranty \rItems \rCovered","Warranty \rItems \rNot \rCovered","Quantity","Minimum \rQuantity","Maximum \rQuantity","Main Image \rName","Other Image \rName 1",
                    "Other Image \rName 2","Other Image \rName 3"
                );
                $rows[3] = array('','(Mandatory)','(Mandatory)',
                    '%(Mandatory)','(Mandatory)','(Mandatory)','','(Mandatory)','','','','','','','',
                    '(Mandatory)','(Mandatory)','(Mandatory)','(Mandatory)','',
                    '','','','','','','(Mandatory)','(Mandatory)','(Mandatory)','','','',''
                );
                /*$rows[4] = array(
                    "This field \rnon \reditable. \rAnd value \rfor it display \rin exported \rexcel sheet.",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rshould be \rnot editable \rand \rcalculated \rvalue using \rformula \rdisplay after \rbase price \rgiven by \rseller.",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rnon \reditable. \rAnd value \rfor it display \rin exported \rexcel sheet.",
                    "This field \rshould be \rnot editable \rand \rcalculated \rvalue using \rformula \rdisplay after \rbase price \rgiven by \rseller.",
                    "This field \rnon \reditable. \rAnd value \rfor it display \rin exported \rexcel sheet.",
                    "This field \rshould be \rnot editable \rand \rcalculated \rvalue using \rformula \rdisplay after \rbase price \rgiven by \rseller.",
                    "This field \rvalue \rdisplay as \rseller \rselect from \rdropdown.",
                    "This field \rshould be \rnot editable \rand \rcalculated \rvalue using \rformula \rdisplay after \rbase price \rand tax \rpercentage \rgiven by \rseller.",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue \rdisplay as \rseller \rselect from \rdropdown.",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue \rdisplay as \rseller \rselect from \rdropdown.",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue \rdisplay as \rseller \rselect from \rdropdown.",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue \rdisplay as \rseller \rselect from \rdropdown.",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself",
                    "This field \rvalue enter \rby seller \ritself. In this \rhe enter \rimage name.",
                    "This field \rvalue enter \rby seller \ritself. In this \rhe enter \rimage name.",
                    "This field \rvalue enter \rby seller \ritself. In this \rhe enter \rimage name.",
                    "This field \rvalue enter \rby seller \ritself. In this \rhe enter \rimage name.",
                );*/
                $rows[5] = array(
                    "SKU ID is the \ridentification \rnumber \rmaintained by \rAgrosiaa to \rkeep track of \rSKUs. This is a \rnon-viewable \rcolumn on the \rproduct page \ron the website.",
                    "Seller SKU \rID is the \ridentification \rnumber \rmaintained by \rseller to keep \rtrack of SKUs. \rThis will be \rmapped with \rAgrosiaa Serial \rNumber. This \ris a \rnon-viewable \rcolumn on the \rproduct page \ron the website.",
                    "Product Name \rrefers to the \rcommon name \rof the product \rthat is widely \rused.",
                    "GST% refers \rto the \rpercentage of \rtax at which the \rproduct will be \rbilled to the \rcustomer.",
                    "Key Spec can \rlist an \rimportant \rspecification of \rthe product, \rwhich will be \rhelpful to the \rcustomer. This \rwill show \rbelow the Title \ron the Website. \rThe data \rentered here \rshould be less \rthan 22 \rcharacters \r(including spaces).",
                    "Key Spec can \rlist an \rimportant \rspecification of \rthe product, \rwhich will be \rhelpful to the \rcustomer. This \rwill show \rbelow the Title \ron the Website. \rThe data \rentered here \rshould be less \rthan 22 \rcharacters \r(including spaces).",
                    "Key Spec can \rlist an \rimportant \rspecification of \rthe product, \rwhich will be \rhelpful to the \rcustomer. This \rwill show \rbelow the Title \ron the Website. \rThe data \rentered here \rshould be less \rthan 22 \rcharacters \r(including spaces).",
                    "Search words \rrefers to the \rwords which \rthe user might \rtype for finding \ra particular \rproduct. These \rsearch words \rare tagged to \rthe product and \rthe appropriate \rsearch words \rgive the exact \rproduct in \rquicker time.",
                    "Weight refers \rto the weight of \rthe product \ronly, without \rpackaging.",
                    "Weight - \rMeasuring Unit \rrefers to the \runit used to \rmeasure the \rweight. Units \rshould be filled \ronly in singular \rform.",
                    "Packing \rDimensions \rrefers to the \rheight x length \rx width of the \rbox or bag in \rwhich the \rproduct is \rpacked for \rdispatch.",
                    "Packing \rDimension - \rMeasuring Unit \rrefers to the \runit used to \rmeasure length, \rwidth and \rheight of the \rbox. Units \rshould be filled \ronly in singular \rform. Possible \rvalues are cm, \rm, etc.",
                    "Final Weight of \rthe packed \rmaterial refers \rto the weight of \rthe product \rwith packaging. \rPossible values \rare 1, 2, etc.",
                    "Weight - \rMeasuring Unit \rrefers to the \runit used to \rmeasure length. \rUnits should be \rfilled only in \rsingular form. \rPossible values \rare gm, kg etc.",
                    "Product \rPick-Up \raddress refers \rto the address \rat which this \rproduct will be \ravailable for the \rlogistics \rpartner for \rpick-up.",
//                    "Product \rPick-Up Pin \rCode refers to \rthe pin code at \rwhich this \rproduct will be \ravailable for the \rlogistics \rpartner for \rpick-up.",
                    "Product \rDescription \rrefers to the \rdetailed \rproduct details \rrequired to be \rdisplayed on \rthe website.",
                    "Brand refers to \ra type of \rProduct \rmanufactured \rby a company \runder a \rparticular \rname.",
                    "Model Number \rrefers to the \rnumber \rassigned by the \rmanufacturers \rto identify the \rdifferent \rProduct that \rthey offer.",
                    "Other Features \r& applications \rrefers to any \radditional \rinformation on \rthe features and \rthe applications \rof the product \rthat would be \ruseful to a \rcustomer.",
                    "Sales Package \rgives the \rinformation of \rall the contents \rthat is sold in \rthe package. \rData should be \rmentioned \rDouble Colon \r(||) seperated.",
                    "Domestic \rWarranty refers \rto the number \rof years that \rthe brand \rsupports the\r domestic \rwarranty of the \rproduct.",
                    "Domestic \rWarranty - \rMeasuring Unit \rrefers to the \runit used to \rmeasure the \rdomestic \rwarranty. Units \rshould be filled \ronly in singular \rform.",
                    "This refers to a \rbrief \rdescription on \rthe warranty \rdetails of the \rproduct. Mostly \rone-lined \rinformation, \rthis should \rcompulsorily \rhave the \rnumber of \ryears covered \rin warranty and \rthe type \r(domestic or \rinternational) \rof warranty.",
                    "This attribute \rcovers the \rprocess on the \rcustomer's part \rto claim the \rwarranty. In \rcase of any \rrepair/damage \rto the product, \rwho/where the \rcustomer has to \rcontact and \rhow would the \rcompany then \rprocess the \rcustomer's \rrequest (either \rby sending an \rengineer or by \rasking the \rcustomer to \rbring the \rproduct at a \rcertain Service \rCenter etc).",
                    "Covers specific \rdetails on what \rwould actually \rthe warranty \rcover.",
                    "This elaborates \ron the things \rthat, if \rdamaged, will \rnot be \rrepaired/covered \runder warranty.",
                    "",
                    "",
                    "",
                    "Main image \rname refers \rto main \rimage of \rthat product",
                    "Other image \rname 1 \rrefers to \radditional \rimage for \rproduct.",
                    "Other image \rname 2 \rrefers to \radditional \rimage for \rproduct.",
                    "Other image \rname 3 \rrefers to \radditional \rimage for \rproduct.",
                );
                $rows[6] = array(
                    "Text - limited \rto 64 characters \r(including \rspaces)",
                    "Text - limited \rto 64 characters \r(including \rspaces)",
                    "Single - Text \rUsed For: ID, \rTitle, Refiner",
                    "Text",
                    "Multi - Text",
                    "Multi - Text",
                    "Multi - Text",
                    "Multi - Text",
                    "Single - \rDecimal",
                    "Single - Text",
                    "Multi - Text, \rDecimal",
                    "Single - Text",
                    "Single - Decimal",
                    "Single - Text",
                    "Multi - Text, \rDecimal",
//                    "Multi - \rDecimal",
                    "Multi - Text, \rDecimal",
                    "Single - Text \rUsed For: ID, \rTitle, Refiner",
                    "Single - Text \rUsed For: ID, \rTitle",
                    "Multi - Text",
                    "Multi - Text",
                    "Single - Number",
                    "Single - Text",
                    "Multi - Text",
                    "Multi - Text",
                    "Multi - Text",
                    "Multi - Text",
                    "Single - Text",
                    "Single - Text",
                    "Single - Text",
                    "Single - Text",
                    "Single - Text",
                    "Single - Text",
                    "Single - Text",
                );
                $rows[7] = array(
                    "",
                    "",
                    "LLDPE \rMulching \rFilm",
                    "GST 0",
                    "Black / \rSilver",
                    "48inches x \r25micron x \r400mtrs",
                    "U/V \rStabilised",
                    "Mulch; \rPlastic Film; \rMulching Film; \rProtective Film; Poly \rFilm; \rMulching Paper",
                    "28",
                    "kgs",
                    "48 x 12 x 6",
                    "inch",
                    "28.2",
                    "kg",
                    "105, New \rTimber \rMarket, \rPune",
//                    "411042",
                    "LDPE UV \rStabilised \rMulching \rFilm",
                    "Grow Rich",
                    "GR4825BS",
                    "Benefits:\r - Reduces \rEvaporation - Early \ryields - Fewer \rWeed Problems - Reduces \rFertilizer \rLeaching - Reduced \rSoil \rCompaction - Cleaner \rVegetable \rProduced - Increases \rGrowth",
                    "2 Roll per \rBag",
                    "0",
                    "year",
                    "Not \rapplicable",
                    "Not \rapplicable",
                    "Not \rapplicable",
                    "Not \rapplicable",
                    "1",
                    "1",
                    "1",
                    "36699.JPG",
                    "36699-1.JPG",
                    "36699-2.JPG",
                    "36699-3.JPG",
                );
                $sheetIndex = 2;
                foreach($itemHeads as $subcategory) {
                    // Create a new worksheet, after the default sheet
                    $objPHPExcel->createSheet();
                    $objPHPExcel->getSheet($sheetIndex)->setTitle($subcategory['tab_name']);
                    /* Add Features to CSV if available */
                    $featureOptions = NULL;
                    $features = Feature::where('category_id', $subcategory['id'])->with('inputs', 'options', 'measuringUnits')->get();
                    if (!$features->isEmpty()) {
                        $featureIndex = 0;
                        foreach ($features as $feature) {
                            if ($feature->required) {
                                $required = '(Mandatory)';
                            } else {
                                $required = '';
                            }
                            $featureOptions[$featureIndex]['header'] = array(
                                $feature->name,
                                $required,
                                "$feature->excel_column_description",
                                "$feature->excel_column_input_type_description",
                                "$feature->excel_column_example"
                            );
                            if ($feature->inputs->slug == 'select') {
                                $featureOptions[$featureIndex]['type'] = 'select';
                                foreach ($feature->options as $options) {
                                    $featureOptions[$featureIndex]['data'][] = $options['name'];
                                }
                            } elseif ($feature->inputs->slug == 'text' && $feature->measuring_unit_id != null) {
                                $featureOptions[$featureIndex]['type'] = 'text';
                                $featureOptions[++$featureIndex]['type'] = 'text-units';
                                $featureOptions[$featureIndex]['header'] = array(
                                    $feature->name . " \rMeasuring-Unit",
                                    $required,
                                    "$feature->excel_column_measurable_unit_description",
                                    "$feature->excel_column_measurable_unit_input_type_description",
                                    "$feature->excel_column_measurable_unit_example"
                                );
                                $featureOptions[$featureIndex]['data'] = $feature->measuringUnits->values;
                            } elseif ($feature->inputs->slug == 'text' && $feature->measuring_unit_id == null) {
                                $featureOptions[$featureIndex]['type'] = 'text';
                            }
                            $featureIndex++;
                        }
                    } else {
                        $featureOptions = null;
                    }
                    /* Feature Functionality End */
                    /* Start Filling Header Columns */
                    /* Add some data to the second sheet, resembling some different data types */
                    $objPHPExcel->setActiveSheetIndex($sheetIndex)
                        ->mergeCells("E1:G1")->mergeCells("I1:O1")->mergeCells("P1:Z1")
                        ->mergeCells("AA1:AC1")
                        ->mergeCells("AD1:AG1");

                    /* Allign text center in PHPExcel merged cell */
                    $objPHPExcel->getActiveSheet()
                        ->setCellValue('C1', 'Title')
                        ->setCellValue('D1', 'TAX Details')
                        ->setCellValue('E1', 'LISTING PAGE')
                        ->setCellValue('I1', 'FOR LOGISTICS PARTNER ( All fields are Mandatory)')
                        ->setCellValue('P1', 'PRODUCT DETAILS AND TECHNICAL SPECIFICATIONS')
                        ->setCellValue('AA1', 'Quantity')
                        ->setCellValue('AD1', 'Images');

                    /* Fill Color In Excel Cell */
                    $objPHPExcel->getActiveSheet()
                        ->getStyle('A1:A3')
                        ->getFill()
                        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('DAEEF3');
                    $objPHPExcel->getActiveSheet()
                        ->getStyle('A4:A5')
                        ->getFill()
                        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FF0000');
                    $objPHPExcel->getActiveSheet()
                        ->getStyle('C1:C5')
                        ->getFill()
                        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFFF00');
                    $objPHPExcel->getActiveSheet()
                        ->getStyle('E1:G5')
                        ->getFill()
                        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('92D050');
                    $objPHPExcel->getActiveSheet()
                        ->getStyle('I1:O5')
                        ->getFill()
                        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFC000');
                    $objPHPExcel->getActiveSheet()
                        ->getStyle('P1:Z5')
                        ->getFill()
                        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('E5B8B7');
                    $objPHPExcel->getActiveSheet()
                        ->getStyle('AA1:AC5')
                        ->getFill()
                        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FF9653');
                    $objPHPExcel->getActiveSheet()
                        ->getStyle('AD1:AG5')
                        ->getFill()
                        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('96FF86');
                    $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);
                    $objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(40);
                    // Add some data to the second sheet, resembling some different data types
                    $objPHPExcel->setActiveSheetIndex($sheetIndex);
                    // Rename 2nd Tab
                    $rowNumber = 2;
                    $column = 'A';
                    foreach ($rows as $row) {
                        $objPHPExcel->getActiveSheet()->getRowDimension($rowNumber)->setRowHeight(-1);
                        foreach ($row as $singleRow) {
                            /* Align Center */
                            $objPHPExcel->getActiveSheet()
                                ->getStyle($objPHPExcel->getActiveSheet()->calculateWorksheetDimension())
                                ->getAlignment()
                                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                                ->setWrapText(true);
                            /* Set Cell Width */
                            $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(20);
                            $objPHPExcel->getActiveSheet()->setCellValue($column . $rowNumber, $singleRow);
                            $column++;
                        }
                        $column = 'A';
                        $rowNumber++;
                    }
                    /* Add Data Till X rows */
                    $excelRows = env('EXCEL_ROWS');
                    $columnForData = 'A';
                    $rowIndex = env('ROW_INDEX');
                    $excelRows = $excelRows + $rowIndex;
                    $agrosiaaSKU = $subcategory['sku'];
                    for (; $rowIndex < $excelRows; $rowIndex++) {
                        $objPHPExcel->getActiveSheet()->setCellValue($columnForData . $rowIndex, $agrosiaaSKU);
                    }
                    /* Add Data Till X rows END*/
                    /* Add Tax Dropdown */
                    $excelRows = env('EXCEL_ROWS');
                    $taxColumn = 'D';
                    $rowIndex = env('ROW_INDEX');
                    $excelRows = $excelRows + $rowIndex;
                   // $taxId = HSNCodeTaxRelation::where('id',$subcategory['hsn_code_id'])->pluck('tax_id');
                   // $taxCode = Tax::where('id',$taxId)->pluck('code');
                    for (; $rowIndex < $excelRows; $rowIndex++) {
                        $objPHPExcel->getActiveSheet()->setCellValue($taxColumn . $rowIndex, "");
                    }
                    /* Product Pickup Address */
                    $excelRows = env('EXCEL_ROWS');
                    $brandColumn = 'O';
                    $rowIndex = env('ROW_INDEX');
                    $excelRows = $excelRows + $rowIndex;
                    $sellerAddressMasterList = implode(", ", $sellerAddress);
                    for (; $rowIndex < $excelRows; $rowIndex++) {
                        $objValidation = $objPHPExcel->getActiveSheet()->getCell($brandColumn . $rowIndex)->getDataValidation();
                        $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                        $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                        $objValidation->setAllowBlank(false);
                        $objValidation->setShowInputMessage(true);
                        $objValidation->setShowErrorMessage(true);
                        $objValidation->setShowDropDown(true);
                        $objValidation->setErrorTitle('Input error');
                        $objValidation->setError('Value is not in list.');
                        $objValidation->setPromptTitle('Pick from list');
                        $objValidation->setPrompt('Please pick a value from the drop-down list.');
                        $objValidation->setFormula1('"' . $sellerAddressMasterList . '"');
                    }
                    /* Product Pickup Address */
                    /* Add Brands Dropdown */
                    $brandList = NULL;
                    $brandMaster = BrandCategory::where('category_id',$subcategory['id'])->with('brands')->limit(20)->get();
                    if(!$brandMaster->isEmpty()){
                        $excelRows = env('EXCEL_ROWS');
                        $brandColumn = 'Q';
                        $rowIndex = env('ROW_INDEX');
                        $excelRows = $excelRows + $rowIndex;
                        foreach($brandMaster as $brandMasterOptions){
                            $brandList[] = $brandMasterOptions['brands']->name;
                        }
                        $brandMasterList = implode(", ", $brandList);
                        for (; $rowIndex < $excelRows; $rowIndex++) {
                            $objValidation = $objPHPExcel->getActiveSheet()->getCell($brandColumn . $rowIndex)->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('"' . $brandMasterList . '"');
                        }
                    }
                    /* Brand Column Dropdown END */
                    /* Add Weight/Packaging/Warranty Units Dropdown*/
                    /* Weight Units */
                    $excelRows = env('EXCEL_ROWS');
                    $rowIndex = env('ROW_INDEX');
                    $excelRows = $excelRows + $rowIndex;
                    $weightUnitColumn = 'J';
                    for (; $rowIndex < $excelRows; $rowIndex++) {
                        $objValidation = $objPHPExcel->getActiveSheet()->getCell($weightUnitColumn . $rowIndex)->getDataValidation();
                        $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                        $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                        $objValidation->setAllowBlank(false);
                        $objValidation->setShowInputMessage(true);
                        $objValidation->setShowErrorMessage(true);
                        $objValidation->setShowDropDown(true);
                        $objValidation->setErrorTitle('Input error');
                        $objValidation->setError('Value is not in list.');
                        $objValidation->setPromptTitle('Pick from list');
                        $objValidation->setPrompt('Please pick a value from the drop-down list.');
                        $objValidation->setFormula1('"' . $weightUnits . '"');
                    }
                    /* Packaging Units */
                    $excelRows = env('EXCEL_ROWS');
                    $rowIndex = env('ROW_INDEX');
                    $excelRows = $excelRows + $rowIndex;
                    $packagingUnitColumn = 'L';
                    for (; $rowIndex < $excelRows; $rowIndex++) {
                        $objValidation = $objPHPExcel->getActiveSheet()->getCell($packagingUnitColumn . $rowIndex)->getDataValidation();
                        $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                        $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                        $objValidation->setAllowBlank(false);
                        $objValidation->setShowInputMessage(true);
                        $objValidation->setShowErrorMessage(true);
                        $objValidation->setShowDropDown(true);
                        $objValidation->setErrorTitle('Input error');
                        $objValidation->setError('Value is not in list.');
                        $objValidation->setPromptTitle('Pick from list');
                        $objValidation->setPrompt('Please pick a value from the drop-down list.');
                        $objValidation->setFormula1('"' . $dimensionUnits . '"');
                    }
                    /* Weight Units */
                    $excelRows = env('EXCEL_ROWS');
                    $rowIndex = env('ROW_INDEX');
                    $excelRows = $excelRows + $rowIndex;
                    $weightUnitColumn = 'N';
                    for (; $rowIndex < $excelRows; $rowIndex++) {
                        $objValidation = $objPHPExcel->getActiveSheet()->getCell($weightUnitColumn . $rowIndex)->getDataValidation();
                        $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                        $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                        $objValidation->setAllowBlank(false);
                        $objValidation->setShowInputMessage(true);
                        $objValidation->setShowErrorMessage(true);
                        $objValidation->setShowDropDown(true);
                        $objValidation->setErrorTitle('Input error');
                        $objValidation->setError('Value is not in list.');
                        $objValidation->setPromptTitle('Pick from list');
                        $objValidation->setPrompt('Please pick a value from the drop-down list.');
                        $objValidation->setFormula1('"' . $weightUnits . '"');
                    }
                    /* Warranty Units */
                    $excelRows = env('EXCEL_ROWS');
                    $rowIndex = env('ROW_INDEX');
                    $excelRows = $excelRows + $rowIndex;
                    $warrantyUnitColumn = 'V';
                    for (; $rowIndex < $excelRows; $rowIndex++) {
                        $objValidation = $objPHPExcel->getActiveSheet()->getCell($warrantyUnitColumn . $rowIndex)->getDataValidation();
                        $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                        $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                        $objValidation->setAllowBlank(false);
                        $objValidation->setShowInputMessage(true);
                        $objValidation->setShowErrorMessage(true);
                        $objValidation->setShowDropDown(true);
                        $objValidation->setErrorTitle('Input error');
                        $objValidation->setError('Value is not in list.');
                        $objValidation->setPromptTitle('Pick from list');
                        $objValidation->setPrompt('Please pick a value from the drop-down list.');
                        $objValidation->setFormula1('"' . $warrantyUnits . '"');
                    }
                    /* End Weight/Packaging/Warranty Units Dropdown */
                    /* Add Features To CSV */
                    if ($featureOptions != null) {
                        $column = 'AH';
                        $rowNumber = 2;
                        $featuresCount = count($featureOptions);
                        $featuresIndex = 1;
                        $objPHPExcel->getActiveSheet()->getRowDimension($rowNumber)->setRowHeight(-1);
                        foreach ($featureOptions as $extraFeatures) {
                            /* Set Cell Width */
                            $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(20);
                            $objPHPExcel->getActiveSheet()->setCellValue($column . "2", $extraFeatures['header'][0]);
                            $objPHPExcel->getActiveSheet()->setCellValue($column . "3", $extraFeatures['header'][1]);
                            $objPHPExcel->getActiveSheet()->setCellValue($column . "4", $extraFeatures['header'][2]);
                            $objPHPExcel->getActiveSheet()->setCellValue($column . "5", $extraFeatures['header'][3]);
                            $objPHPExcel->getActiveSheet()->setCellValue($column . "6", $extraFeatures['header'][4]);
                            if ($extraFeatures['type'] == 'select') {
                                $optionList = implode(", ", $extraFeatures['data']);
                                //Data Validation list
                                /* Add Data Till X rows */
                                $excelRows = env('EXCEL_ROWS');
                                $rowIndex = env('ROW_INDEX');
                                $excelRows = $excelRows + $rowIndex;
                                for (; $rowIndex < $excelRows; $rowIndex++) {
                                    $objValidation = $objPHPExcel->getActiveSheet()->getCell($column . $rowIndex)->getDataValidation();
                                    $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                                    $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                                    $objValidation->setAllowBlank(false);
                                    $objValidation->setShowInputMessage(true);
                                    $objValidation->setShowErrorMessage(true);
                                    $objValidation->setShowDropDown(true);
                                    $objValidation->setErrorTitle('Input error');
                                    $objValidation->setError('Value is not in list.');
                                    $objValidation->setPromptTitle('Pick from list');
                                    $objValidation->setPrompt('Please pick a value from the drop-down list.');
                                    $objValidation->setFormula1('"' . $optionList . '"');
                                }
                            }
                            if ($extraFeatures['type'] == 'text') {
                                //
                            }
                            if ($extraFeatures['type'] == 'text-units') {
                                //Data Validation list
                                /* Add Data Till X rows */
                                $excelRows = env('EXCEL_ROWS');
                                $rowIndex = env('ROW_INDEX');
                                $excelRows = $excelRows + $rowIndex;
                                for (; $rowIndex < $excelRows; $rowIndex++) {
                                    $objValidation = $objPHPExcel->getActiveSheet()->getCell($column . $rowIndex)->getDataValidation();
                                    $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                                    $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                                    $objValidation->setAllowBlank(false);
                                    $objValidation->setShowInputMessage(true);
                                    $objValidation->setShowErrorMessage(true);
                                    $objValidation->setShowDropDown(true);
                                    $objValidation->setErrorTitle('Input error');
                                    $objValidation->setError('Value is not in list.');
                                    $objValidation->setPromptTitle('Pick from list');
                                    $objValidation->setPrompt('Please pick a value from the drop-down list.');
                                    $objValidation->setFormula1('"' . $extraFeatures['data'] . '"');
                                }
                            }
                            /* Align Center */
                            $objPHPExcel->getActiveSheet()
                                ->getStyle($objPHPExcel->getActiveSheet()->calculateWorksheetDimension())
                                ->getAlignment()
                                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                                ->setWrapText(true);
                            /* Set Cell Width */
                            $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(20);
                            if ($featuresIndex<$featuresCount) {
                                $column++;
                            }
                        }
                        $objPHPExcel->setActiveSheetIndex($sheetIndex)->mergeCells("AH1:" . $column . "1");
                        /* Align text center in PHPExcel merged cell */
                        $objPHPExcel->getActiveSheet()->setCellValue('AH1', 'Features');
                    }
                    /* Make Sheet readonly except some row/columns */
                    if ($column == 'A') {
                        $highestColumn = $objPHPExcel->getActiveSheet()->getHighestDataColumn();
                    } else {
                        $highestColumn = $column;
                    }
                    /* Apply Border Till Highest Column START*/
                    $styleArray = array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    );
                    $objPHPExcel->getActiveSheet()->getStyle("A1:$highestColumn"."5")->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()
                        ->getStyle("AH1:$highestColumn"."5")
                        ->getFill()
                        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('8C70FF');
                    /* Apply Border Till Highest Column END*/
                    $excelRows = env('EXCEL_ROWS');
                    $rowIndex = env('ROW_INDEX');
                    $excelRows = $excelRows + $rowIndex - 1;
                    $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
                    /*$objPHPExcel->getActiveSheet()
                        ->getStyle("B8:" . "C" . $excelRows)
                        ->getProtection()
                        ->setLocked(
                            \PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
                        );
                    $objPHPExcel->getActiveSheet()
                        ->getStyle("E8:" . "E" . $excelRows)
                        ->getProtection()
                        ->setLocked(
                            \PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
                        );
                    $objPHPExcel->getActiveSheet()
                        ->getStyle("J8:" . "J" . $excelRows)
                        ->getProtection()
                        ->setLocked(
                            \PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
                        );*/
                    $objPHPExcel->getActiveSheet()
                        ->getStyle("B8:" . $highestColumn . $excelRows)
                        ->getProtection()
                        ->setLocked(
                            \PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
                        );
                    /* Readonly End */
                    /* Freeze First 3 Rows START */
                    $objPHPExcel->getActiveSheet()->freezePane("A4");
                    /* Freeze First 3 Rows END */
                    $sheetIndex++;
                }
                $objPHPExcel->setActiveSheetIndex(1);
                /* Caching */
                $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_in_memory;
                \PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
                // Redirect output to a client’s web browser (Excel2007)
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
                $fileName = $company." - Categories - Agrosiaa ".$selectedCategory['tab_name'].".xlsx";
                header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                header("Content-Disposition: attachment; filename=\"".$fileName."\"");
                header("Cache-Control: max-age=0");
                //$objWriter->save('results.xlsx');
                ob_end_clean();
                $objWriter->save("php://output");
                exit();
            }else{
                // No Item Heads Found
                $message = "No item Heads found for selected category";
                $request->session()->flash('error', $message);
                if($request->has('seller_id')){
                    return redirect('operational/administration/export');
                }else{
                    return redirect('administration/export');
                }
            }
        }catch (\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'CSV Export View',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function csvImportView(Request $request){
        try{
            $data = [
                'is_active' => 1,
                'category_id' => null,
            ];
            $rootCategories = Category::where($data)->get()->toArray();
            $roleType = $this->userRoleType;
            if($roleType=='superadmin'){
                $sellerRole = Role::where('slug','seller')->first();
                $sellers = User::where(['is_active'=>1,'role_id'=>$sellerRole->id])->get();
                return view('backend.superadmin.products.import-csv')->with(compact('rootCategories','sellers'));
            }elseif($roleType=='seller'){
                return view('backend.seller.product.import-csv')->with(compact('rootCategories'));
            }
        }catch (\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'CSV Import View',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function csvImport(ExcelRequest $request){
        try{
            $selectedCategory = Category::findBySlugOrFail($request->category);
            $reader = ReaderFactory::create(Type::XLSX); // for XLSX files
            $reader->open($request->file('excel_file'));
            /* Validate Tab Names First */
            $sheetIndex = 1;
            foreach ($reader->getSheetIterator() as $sheet) {
                $tabName = $sheet->getName();
                if($sheetIndex==1 && $tabName!='Summary Sheet'){
                    $message = 'Invalid Excel File First Tab should be Summary Sheet';
                    $request->session()->flash('error', $message);
                    if($request->has('seller_id')){
                        return redirect('operational/administration/import');
                    }else{
                        return redirect('administration/import');
                    }
                }
                /*if($sheetIndex==2 && $tabName!='Main Index'){
                    $message = 'Invalid Excel File Second Tab should be Main Index';
                    $request->session()->flash('error', $message);
                    return redirect('administration/import');
                }*/
                if($sheetIndex==2 && $tabName!=$selectedCategory->tab_name){
                    $csvCategory = Category::where('tab_name',$tabName)->first();
                    if($csvCategory!=null){
                        $message = "Selected Category is $selectedCategory->name & uploaded csv belongs to $csvCategory->name";
                    }else{
                        $message = "Selected Category is $selectedCategory->name & uploaded csv not belongs to any category";
                    }
                    $request->session()->flash('error', $message);
                    if($request->has('seller_id')){
                        return redirect('operational/administration/import');
                    }else{
                        return redirect('administration/import');
                    }
                }
                if($sheetIndex>=3){
                    $findCategoryByTabName = Category::where('tab_name',$tabName)->first();
                    if($findCategoryByTabName!=null && $findCategoryByTabName->is_item_head){
                        if($findCategoryByTabName->category_id!=null){
                            $findParentCategory = Category::find($findCategoryByTabName->category_id);
                            if($findParentCategory->category_id!=null){
                                $findParentsParentCategory = Category::find($findParentCategory->category_id);
                                if($findParentsParentCategory->id!=$selectedCategory->id){
                                    $message = "This $tabName is not belongs to selected category.";
                                    $request->session()->flash('error', $message);
                                    if($request->has('seller_id')){
                                        return redirect('operational/administration/import');
                                    }else{
                                        return redirect('administration/import');
                                    }
                                }
                            }else{
                                if($findParentCategory->id!=$selectedCategory->id){
                                    $message = "This $tabName is not belongs to selected category.";
                                    $request->session()->flash('error', $message);
                                    if($request->has('seller_id')){
                                        return redirect('operational/administration/import');
                                    }else{
                                        return redirect('administration/import');
                                    }
                                }
                            }
                        }
                    }else{
                        $message = "This $tabName item head is not exists";
                        $request->session()->flash('error', $message);
                        if($request->has('seller_id')){
                            return redirect('operational/administration/import');
                        }else{
                            return redirect('administration/import');
                        }
                    }
                }
                $sheetIndex++;
            }
            /* Validate Tab Names End */
            /* Upload CSV TO DB */
            $sheetIndex = 1;
            /* Get Pending Status of Query */
            $queryStatus = ProductQueryStatus::where('slug','pending')->first();
            /* Get Seller ID */
            if($request->has('seller_id')){
                $sellerId = $request->seller_id;
            }else{
                $sellerId = $this->seller->id;
            }
            /* Get Current time */
            $timeStamp = Carbon::now();
            foreach ($reader->getSheetIterator() as $sheet) {
                $tabName = $sheet->getName();
                if($sheetIndex>=3){
                    /* Find Item Head ID */
                    $sheetItemHead = Category::where('tab_name',$tabName)->first();
                    $rowIndex = 1;
                    $i = 0;
                    foreach ($sheet->getRowIterator() as $rows) {
                        if($rowIndex==2){ //Get All Headers
                            foreach($rows as $row){
                                $row = preg_replace("/\r|\n/","",$row);
                                $excelData[$tabName]['headers'][] = strtolower(str_replace(" ","_",$row));
                            }
                        }
                        if($rowIndex==3){ //Get mandatory
                            foreach($rows as $row){
                                $excelData[$tabName]['mandatory'][] = strtolower($row);
                            }
                        }
                        /* Create Array To Batch Insert */
                        if($rowIndex>=7){
                            if(!empty($rows[1]) && !empty($rows[2]) /*&& !empty($rows[3])*/ && !empty($rows[27])){
                                $totalColumnsInSheet = count($excelData[$tabName]['headers']);
                                $totalCommonColumns = 34;
                                $totalFeatureColumns = $totalColumnsInSheet - $totalCommonColumns;
                                for($csvColumnIndex = 0;$csvColumnIndex<=33;$csvColumnIndex++){
                                    /* Find Tax ID */
                                    if($excelData[$tabName]['headers'][$csvColumnIndex]=="gst"){
                                        if(!empty($rows[$csvColumnIndex])){
                                            //$tax = Tax::where('code',$rows[$csvColumnIndex])->first();
                                            $tax = null;
                                            if($tax == null){
                                                $data[$i]['tax_id'] = NULL;
                                            }else{
                                                $data[$i]['tax_id'] = $tax->id;
                                            }
                                        }else{
                                            $data[$i]['tax_id'] = NULL;
                                        }

                                    }elseif($excelData[$tabName]['headers'][$csvColumnIndex]=="packing_dimensions"){
                                        /* Packaging Dimensions */
                                        $packagingDimensions = explode('x',$rows[$csvColumnIndex]);
                                        if(count($packagingDimensions)==3){
                                            $data[$i]['height'] = $packagingDimensions[0];
                                            $data[$i]['length'] = $packagingDimensions[1];
                                            $data[$i]['width'] = $packagingDimensions[2];
                                        }
                                    }elseif($excelData[$tabName]['headers'][$csvColumnIndex]=="product_pick_up_address"){
                                        if($request->has('seller_id')){
                                            $sellerId = $request->seller_id;
                                        }else{
                                            $sellerId = $this->seller->id;
                                        }
                                        if(!empty($rows[$csvColumnIndex])){
                                            $address = SellerAddress::where('address_unique_name',$rows[$csvColumnIndex])->where('seller_id',$sellerId)->first();
                                            if($address==null){
                                                $address = SellerAddress::where('address_unique_name','ilike','default%')->where('seller_id',$sellerId)->first();
                                            }
                                        }else{
                                            $address = SellerAddress::where('address_unique_name','ilike','default%')->where('seller_id',$sellerId)->first();
                                        }
                                        $data[$i]['seller_address_id'] = $address->id;
                                    }elseif($excelData[$tabName]['headers'][$csvColumnIndex] == "brand"){
                                        if(!empty($rows[$csvColumnIndex])){
                                            $brand = Brand::where('name','ilike',trim($rows[$csvColumnIndex]))->first();
                                            if($brand != null){
                                                $data[$i]['brand_id'] = $brand->id;
                                            }
                                        }
                                    }else{
                                        $data[$i][$excelData[$tabName]['headers'][$csvColumnIndex]] = preg_replace("/\r|\n/","",$rows[$csvColumnIndex]);;
                                    }
                                }
                                $data[$i]['product_query_status_id'] = $queryStatus->id;
                                $data[$i]['category_id'] = $sheetItemHead->id;
                                $data[$i]['seller_id'] = $sellerId;
                                $data[$i]['created_at'] = $timeStamp;
                                $data[$i]['updated_at'] = $timeStamp;
                                $data[$i]['product_name'] = strtolower($data[$i]['product_name']);
                                $data[$i]['search_keywords'] = strtolower($data[$i]['search_keywords']);
                                $sellerCategoryCount = $this->product_category_count($sellerId,$sheetItemHead->id);
                                $category = Category::where('id',$sellerCategoryCount['category_id'])->first();
                                $seller = Seller::where('id',$sellerCategoryCount['seller_id'])->first();
                                $itemHeadAbbreviation = strtoupper($category['item_head_abbreviation']);
                                $sellerAbbreviation = strtoupper($seller['seller_name_abbreviation']);
                                $data[$i]['item_based_sku'] = 'AGR'.''.$itemHeadAbbreviation.$sellerAbbreviation.str_pad($sellerCategoryCount['count'], 6, "0", STR_PAD_LEFT);
                                $insertedProduct = Product::create($data[$i]);
                                $productCategoryData['product_id'] = $insertedProduct->id;
                                $productCategoryData['category_id'] = $sheetItemHead->id;
                                $productCategoryData['created_at'] = $timeStamp;
                                $productCategoryData['updated_at'] = $timeStamp;
                                $ProductCategory = ProductCategoryRelation::create($productCategoryData);
                                /* Insert Images */
                                $productImages = NULL;
                                if(!empty($rows[30])){
                                    $productImages[] = array('name'=>$rows[30],'position'=>1,'product_id'=>$insertedProduct->id,'created_at'=>$timeStamp,'updated_at'=>$timeStamp);
                                }
                                if(!empty($rows[31])){
                                    $productImages[] = array('name'=>$rows[31],'position'=>2,'product_id'=>$insertedProduct->id,'created_at'=>$timeStamp,'updated_at'=>$timeStamp);
                                }
                                if(!empty($rows[32])){
                                    $productImages[] = array('name'=>$rows[32],'position'=>3,'product_id'=>$insertedProduct->id,'created_at'=>$timeStamp,'updated_at'=>$timeStamp);
                                }
                                if(!empty($rows[33])){
                                    $productImages[] = array('name'=>$rows[33],'position'=>4,'product_id'=>$insertedProduct->id,'created_at'=>$timeStamp,'updated_at'=>$timeStamp);
                                }
                                if($productImages!=null){
                                    ProductImage::insert($productImages);
                                }
                                /* Feature Insert */
                                if($totalFeatureColumns>=0){
                                    $featureArray = null;
                                    for($featureIndex=34;$featureIndex<$totalColumnsInSheet;$featureIndex++){
                                        $featureName = $excelData[$tabName]['headers'][$featureIndex];
                                        if(!empty($featureName)){
                                            $feature = Feature::where('name','ilike',$featureName)->where('category_id',$sheetItemHead->id)->with('inputs')->first();
                                            if($feature!=null){
                                                if($feature->inputs->slug=='select'){
                                                    if(!empty($rows[$featureIndex])){
                                                        $optionValue = "%".$rows[$featureIndex]."%";
                                                    }else{
                                                        $optionValue = '';
                                                    }
                                                    $options = FeatureOption::where('name','ilike',$optionValue)->where('feature_id',$feature->id)->first();
                                                    if($options!=null){
                                                        $featureArray[] = array(
                                                            'product_id' => $insertedProduct->id,
                                                            'feature_id' => $feature->id,
                                                            'feature_text'=>null,
                                                            'feature_measuring_unit'=>null,
                                                            'feature_option_id' => $options->id,
                                                            'created_at' => $timeStamp,
                                                            'updated_at' => $timeStamp,
                                                        );
                                                    }else{
                                                        $featureArray[] = array(
                                                            'product_id' => $insertedProduct->id,
                                                            'feature_id' => $feature->id,
                                                            'feature_text'=>null,
                                                            'feature_measuring_unit'=>null,
                                                            'feature_option_id' => null,
                                                            'created_at' => $timeStamp,
                                                            'updated_at' => $timeStamp,
                                                        );
                                                    }

                                                }
                                                if($feature->inputs->slug=='text'){
                                                    if($feature->measuring_unit_id==null){ //Not Measurable
                                                        $featureArray[] = array(
                                                            'product_id' => $insertedProduct->id,
                                                            'feature_id' => $feature->id,
                                                            'feature_text' => preg_replace("/\r|\n/","",$rows[$featureIndex]),
                                                            'feature_measuring_unit'=>null,
                                                            'feature_option_id'=>null,
                                                            'created_at' => $timeStamp,
                                                            'updated_at' => $timeStamp,
                                                        );
                                                    }else{  //Feature is Measurable
                                                        $featureUnitIndex = $featureIndex + 1;
                                                        $featureArray[] = array(
                                                            'product_id' => $insertedProduct->id,
                                                            'feature_id' => $feature->id,
                                                            'feature_text' => preg_replace("/\r|\n/","",$rows[$featureIndex]),
                                                            'feature_measuring_unit' => $rows[$featureUnitIndex],
                                                            'feature_option_id'=>null,
                                                            'created_at' => $timeStamp,
                                                            'updated_at' => $timeStamp,
                                                        );
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if($featureArray!=null){
                                        ProductFeatureRelation::insert($featureArray);
                                    }
                                }
                                $i++;
                            }
                        }
                        $rowIndex++;
                    }
                }
                $sheetIndex++;
            }
            $reader->close();
            $message = "Product File uploaded successfully";
            $request->session()->flash('success', $message);
            if($request->has('seller_id')){
                return redirect('/operational/administration/import');
            }else{
                return redirect('administration/import');
            }
        }catch (\Exception $e){
            $data = [
                'user' => $this->user,
                'action' => 'CSV Import Functionality',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function csvExportForHSNCode(Request $request){
        try{
            $data[] = array();
            $row = 0;
            $allItemHeads = Category::where('is_item_head',true)->get()->toArray();
            foreach($allItemHeads as $key => $value){
                $data[$row]['slug'] = $value['slug'];
                $row++;
            }
            $taxes = Tax::where('is_active',true)->get();
            $taxes = $taxes->toArray();
            foreach($taxes as $tax){
                $taxMaster[] = $tax['code'];
            }
            $rowNumber = 1;
            $rowIndex = 2;
            $rows[0] = array('Item Head Category Name','HSN Code','Tax');
            $excelTitle = "HSN Code assign to Category";
            $name = "HSN_CODE_TO_Category_data.xlsx";
            $objPHPExcel = new \PHPExcel();
            $objWorkSheet = $objPHPExcel->createSheet();
            $objPHPExcel->getSheet(0)->setTitle($excelTitle);
            $objPHPExcel->setActiveSheetIndex(0);
            $column = 'A';
            foreach ($rows as $row) {
                $objPHPExcel->getActiveSheet()->getRowDimension($rowNumber)->setRowHeight(-1);
                foreach ($row as $singleRow) {
                    /* Align Center */
                    $objPHPExcel->getActiveSheet()
                        ->getStyle($objPHPExcel->getActiveSheet()->calculateWorksheetDimension())
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                        ->setWrapText(true);
                    /* Set Cell Width */
                    $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(20);
                    $objPHPExcel->getActiveSheet()->setCellValue($column . $rowNumber, $singleRow);
                    $column++;
                }
                $column = 'A';
                $rowNumber++;
            }
            foreach($data as $key => $datavalues) {
                $columnForData = 0;
                foreach($datavalues as $datavalue => $value){
                    /* Align Center */
                    $objPHPExcel->getActiveSheet()
                        ->getStyle($objPHPExcel->getActiveSheet()->calculateWorksheetDimension())
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                        ->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnForData, $rowIndex, $value);
                    $columnForData++;
                }
                $rowIndex++;
            }
            $taxColumn = 'C';
            $taxOptionList = implode(", ", $taxMaster);
            for ($rowIndex = 2 ; $rowIndex < count($allItemHeads)+2; $rowIndex++) {
                $objValidation = $objPHPExcel->getActiveSheet()->getCell($taxColumn . $rowIndex)->getDataValidation();
                $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"' . $taxOptionList . '"');
            }
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
            $fileName = $name;
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment; filename=\"".$fileName."\"");
            ob_end_clean();
            $objWriter->save("php://output");
            exit();
            return redirect('/dashboard');
        }catch (\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'CSV Export for HSN code',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function csvImportViewForHSNCode(Request $request){
        try{
            return view('backend.superadmin.category.import-csv-hsn-code');
        }catch(\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'CSV Import View for HSN Code',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function csvImportForHSNCode(Request $request){
        try{
            $data = array();
            $reader = ReaderFactory::create(Type::XLSX); // for XLSX files
            $reader->open($request->file('excel_file'));
            $sheetIndex = 1;
            foreach($reader->getSheetIterator() as $sheet){
                $tabName = $sheet->getName();
                if($sheetIndex==1 && $tabName!='HSN Code assign to Category'){
                    $message = 'Invalid Excel File';
                    $request->session()->flash('error',$message);
                    redirect('operational/administration/import-for-hsn-code');
                }elseif($sheetIndex==1){
                    $rowIndex = 1;
                    $i = 0;
                    foreach ($sheet->getRowIterator() as $rows) {
                        /* Create Array To Batch Insert */
                        if($rowIndex>=2){
                            if($rows[1] != null && $rows[2] != null){
                                $alreadyExistHSNCode = HSNCodes::where('hsn_code',trim($rows[1]))->first();
                                if(count($alreadyExistHSNCode) == 0){
                                    $data['hsn_code'] = trim($rows[1]);
                                    $hsnCode =  HSNCodes::create($data);
                                }else{
                                    $hsnCode = $alreadyExistHSNCode;
                                }
                                $tax_id = Tax::where('code',$rows[2])->pluck('id');
                                $alreadyExistHSNCodeTax = HSNCodeTaxRelation::where('hsn_code_id',$hsnCode['id'])->where('tax_id',$tax_id)->first();
                                if(count($alreadyExistHSNCodeTax) == 0){
                                    $data['tax_id'] = $tax_id;
                                    $data['hsn_code_id'] = $hsnCode['id'];
                                    $hsnCodeTax = HSNCodeTaxRelation::create($data);
                                }else{
                                    $hsnCodeTax = $alreadyExistHSNCodeTax;
                                }
                                $category = Category::where('slug',$rows[0])->first();
                                $alreadyAssignCategoryHSNCodeTax = CategoryHSNCodeTaxRelation::where('category_id',$category['id'])->where('hsn_code_tax_relation_id',$hsnCodeTax['id'])->first();
                                if(count($alreadyAssignCategoryHSNCodeTax) == 0){
                                    $data['category_id'] = $category['id'];
                                    $data['hsn_code_tax_relation_id'] = $hsnCodeTax['id'];
                                    $categoryHSNCodeData = CategoryHSNCodeTaxRelation::create($data);
                                }else{
                                    $categoryHSNCodeData = $alreadyAssignCategoryHSNCodeTax;
                                }
                            }
                        }
                        $rowIndex++;
                    }
                }
                $sheetIndex++;
            }
            $reader->close();
            $message = "Product File uploaded successfully";
            $request->session()->flash('success', $message);
            return redirect('operational/administration/import-for-hsn-code');
        }catch(\Exception $e){
            $data = [
                'user' => $this->user,
                'action' => 'CSV Import Functionality for HSN code',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

}
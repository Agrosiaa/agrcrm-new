/* some custom methos for validations */
var language = $('#language').val();
if(language == 'mr'){
    var lettersonly = "केवळ अक्षरे प्रविष्ट करा";
    var mobile = "मोबाइल अंकी असणे आवश्यक आहे आणि तो 10 अंकी असावा";
    var alphanum = "केवळ अक्षरे आणि जागा अनुमत आहेत";
    var alpha_num_space_sym = "केवळ अक्षरे संख्या & / आणि - अनुमती आहेत";
    var alpha_num_space_allow = "केवळ अल्फा क्रमांक आणि जागा परवानगी दिली आणि जागा परवानगी नाही सुरू आहेत";
    var alphaSpace = "केवळ अक्षरे आणि जागा अनुमत आहेत";
    var alphaSpaceSpecial = "केवळ अक्षरे, / - आणि जागा अनुमत आहेत";
    var areaLocality = "केवळ अक्षरे, @#&,./'- आणि जागा अनुमत आहेत";
    var chkMail = "वैध ई-मेल आयडी नमूद करा.";
    var alpha_num_space = "केवळ अक्षरे आणि जागा अनुमत आहेत";
    var panNumber = "Please Enter valid PAN Number";
    var tanNumber = "Please Enter valid TAN Number";
    var vatNumber = "Please Enter valid VAT Number";
    var cstNumber = "Please Enter valid CST Number";
    var ifscspecificFormat = "Please enter in this format asdc0123456";
    var alphanumericSpecificFormat= "Please enter in this format name0123456";
    var validData = "Please enter valid data";

}else{
    var lettersonly = "Please enter only letters";
    var mobile = "Mobile number should be 10 digits only.";
    var alphanum = "Please enter only alphabets and digits";
    var alpha_num_space_sym = "Only alpha num & / and - are allowed";
    var alpha_num_space_allow = "Only alpha num & space are allowed and starting with space and special char not allowed";
    var alphaSpace = "Please enter only contains alpha and space and starting with alphabet";
    var alphaSpaceSpecial = "Please enter only contains alpha and space and starting with alphabet and special characters allowed are / -";
    var areaLocality = "Please enter only contains alpha and space and starting with alphabet and special characters allowed are @#&,./'-";
    var chkMail = "Please enter valid email address.";
    var alpha_num_space = "Only alpha num & space are allowed and starting with space not allowed";
    var panNumber = "Please Enter valid PAN Number";
    var tanNumber = "Please Enter valid TAN Number";
    var vatNumber = "Please Enter valid VAT Number";
    var cstNumber = "Please Enter valid CST Number";
    var ifscspecificFormat = "Please enter in this format asdc0123456";
    var alphanumericSpecificFormat= "Please enter in this format name0123456";
    var validData = "Please enter valid data";
}
jQuery.validator.addMethod("lettersonly", function(value, element)
{
    return this.optional(element) || /^[A-z]+$/i.test(value);
},lettersonly );
jQuery.validator.addMethod("alpha", function(value, element) {
    return this.optional(element) || /^[a-zA-Z ]+([a-zA-Z])*$/.test(value);
});
jQuery.validator.addMethod("alphanum", function(value, element)
{
    return this.optional(element) || /^[a-zA-Z0-9]*$/i.test(value);
}, alphanum);
jQuery.validator.addMethod("alphanumeric", function(value, element)
{
    return this.optional(element) || /^([a-zA-Z ]{4})+([0-9 ]{7})$/i.test(value);
}, alphanumericSpecificFormat);
jQuery.validator.addMethod("alpha_num_space_sym", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]+([A-Za-z0-9/-])*$/.test(value);
}, alpha_num_space_sym);
jQuery.validator.addMethod("alpha_num_space_allow", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]+([ A-Za-z0-9@./#',&-])*$/.test(value);
},alpha_num_space_allow);
jQuery.validator.addMethod("alphanumsymbols", function(value, element)
{
    return this.optional(element) || /^[ A-Za-z0-9-,.]*$/i.test(value);
}, validData);
jQuery.validator.addMethod("alphanumsymbolslash", function(value, element)
{
    return this.optional(element) || /^[ A-Za-z0-9-/,.]*$/i.test(value);
}, validData);
jQuery.validator.addMethod("alphanumsymbolsquote", function(value, element)
{
    return this.optional(element) || /^[ A-Za-z0-9-/',.]*$/i.test(value);
}, validData);

$.validator.addMethod("ifsc", function(value, element) {
    return this.optional(element) || value == value.match(/^[A-Za-z0-9]{11}$/);
},ifscspecificFormat);
$.validator.addMethod("alphaSpace", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z]+[a-zA-Z\s]+$/);
}, alphaSpace);
$.validator.addMethod("alphaSpaceSpecial", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z0-9]+[a-zA-Z0-9\s/-]+$/);
},alphaSpaceSpecial);
$.validator.addMethod("areaLocality", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z0-9]+[a-zA-Z0-9\s@#&,./'-]+$/);
},areaLocality);
$.validator.addMethod("chkMail", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(value);
},chkMail);
$.validator.addMethod("alpha_num_space", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]+([a-zA-Z0-9\s])*$/.test(value);
}, alpha_num_space);

$.validator.addMethod("pan", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z]{5}[0-9]{4}[A-Za-z]{1}$/);
},panNumber);
$.validator.addMethod("tan", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z]{4}[0-9]{5}[A-Za-z]{1}$/);
},tanNumber);
$.validator.addMethod("vat", function(value, element) {
    return this.optional(element) || value == value.match(/^[0-9]{11}[Vv]{1}$/);
},vatNumber);
$.validator.addMethod("cst", function(value, element) {
    return this.optional(element) || value == value.match(/^[0-9]{11}[Cc]{1}$/);
},cstNumber);
$.validator.addMethod("mobile", function(value, element) {
    return this.optional(element) || value == value.match(/^[0-9]{10}$/);
}, mobile);
$.validator.addMethod("chkMail", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(value);
},chkMail);

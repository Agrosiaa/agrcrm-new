{{var_dump($errors->all())}}
{{var_dump(Session::all())}}
<form action="/post-temp" method="POST">
    <input type="text" name="email">
    <input type="text" name="password">
    <input type="submit" name="submit">
</form>
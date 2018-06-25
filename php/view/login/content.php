<?php if (count($vd->getErrorMessages()) == 0) {
    ?><div id="title-logo"><h1>mooc</h1></div><?php
}?>
<form method="post" id="login-form" action="login">
    <h2>Login</h2>
    <label for="username">Username</label><br/>
    <input name="username" id="username" type="text" class="textbox">
    <br/>
    <label for="password">Password</label><br/>
    <input name="password" id="password" type="password" class="textbox">
    <br/>
    <input type="hidden" name="cmd" value="login"/>
    <div class="submit-container">
        <button type="submit">Login</button>
    </div>
</form>


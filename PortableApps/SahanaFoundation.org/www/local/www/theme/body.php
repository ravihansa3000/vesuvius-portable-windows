<?

/**
 * @name         Lost Person Finder Theme
 * @version      0.1
 * @package      lpf
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0811
 */
function shn_theme_body_register_form($error_list) {
    global $global;
    $portable_conf_data = false;
    if ($error_list === false) {
        $portable_conf_data = get_portable_conf_data();
        $owner_name = ($portable_conf_data === false) ? '' : $portable_conf_data['owner_name'];
        $portable_desc = ($portable_conf_data === false) ? '' : $portable_conf_data['portable_desc'];
        $organization = ($portable_conf_data === false) ? '' : $portable_conf_data['organization'];
        $parent_base_uuid = ($portable_conf_data === false) ? $global['sahana.base_uuid'] : $portable_conf_data['parent_base_uuid'];
        $owner_email = ($portable_conf_data === false) ? '' : $portable_conf_data['owner_email'];
        $user_name = '';
    } else {
        $owner_name = htmlspecialchars(trim($_POST['owner_name']));
        $portable_desc = htmlspecialchars(trim($_POST['portable_desc']));
        $organization = htmlspecialchars(trim($_POST['organization']));
        $parent_base_uuid = htmlspecialchars(trim($_POST['parent_base_uuid']));
        $owner_email = htmlspecialchars(trim($_POST['owner_email']));
        $user_name = htmlspecialchars(trim($_POST['user_name']));

        $err_style = "style='color: red;'";
    }
    ?><body>
        <div id="container">
            <div id="header" class="clearfix">
                <div id="leftHeaderLogo"><a href="#"><img id="leftHeaderLogoImg" src="theme/img/pl.png" alt="People Locator Logo"></a><sup id="suplogo" style="font-size: 120%; color: #34689A;">&#0153;</sup>
                </div>
                <div id="rightHeaderLogo"><a href="#"><img src="theme/img/NLMlogoSmall.gif" alt="United States National Library of Medicine Logo"></a>
                </div>
            </div>
            <div id="headerText">
                <h1>Sahana Vesuvius<sup>&#0153;</sup></h1>
                <h3>&nbsp;</h3>
                <h4>U.S. National Library of Medicine</h4>
                <h4>Lister Hill National Center for Biomedical Communications</h4>
            </div>
            <div id="wrapper" class="clearfix">

                <!-- Left hand side menus & login form -->
                <div id="content" class="clearfix">
                    <div style="padding: 0px 0px 0px 36px;">
                        <h3>Vesuvius Portable has detected a machine movement. Please take a moment to register this instance.</h3>
                        <br/>
                        <? if ($error_list !== false) { ?>
                            <h4 style="color: red;">Sorry, but an error has been made. </h4>
                            <br/>
                            <h4>Please check the field(s) marked in red</h4>
                        <? } ?>
                    </div>
                    <div>
                        <form name="input" action="index.php" method="POST">
                            <table id="regform" >
                                <tr>
                                    <td <? if ($error_list !== false && in_array("owner_name", $error_list)) echo $err_style; ?> >Owner's name</td>
                                    <td><input type="text" name="owner_name" value="<? echo $owner_name; ?>" size="40"></td>
                                    <td>Enter your full name (length: Max - 30, Min - 4)</td>
                                </tr>
                                <tr>
                                    <td <? if ($error_list !== false && in_array("portable_desc", $error_list)) echo $err_style; ?> >Description</td>
                                    <td><input type="text" name="portable_desc" value="<? echo $portable_desc; ?>" size="40"></td>
                                    <td>Description of this portable instance (length: Max - 50)</td>
                                </tr>
                                <tr>
                                    <td <? if ($error_list !== false && in_array("organization", $error_list)) echo $err_style; ?> >Organization Name</td>
                                    <td><input type="text" name="organization" value="<? echo $organization; ?>" size="40"></td>
                                    <td>Enter organization name (length: Max - 30, Min - 3)</td>
                                </tr>
                                <tr>
                                    <td <? if ($error_list !== false && in_array("parent_base_uuid", $error_list)) echo $err_style; ?> >Parent instance base_uuid</td>
                                    <td><input type="text" name="parent_base_uuid" value="<? echo $parent_base_uuid; ?>" size="40"></td>
                                    <td>The unique identifier of parent instance (must end with a trailing slash, leave unchanged if you are not sure)</td>
                                </tr>
                                <tr>
                                    <td <? if ($error_list !== false && in_array("owner_email", $error_list)) echo $err_style; ?> >Owner's email</td>
                                    <td><input type="text" name="owner_email" value="<? echo $owner_email; ?>" size="40"></td>
                                    <td>Enter your email address</td>
                                </tr>
                                <tr>
                                    <td <? if ($error_list !== false && in_array("user_name", $error_list)) echo $err_style; ?> >Username</td>
                                    <td><input type="text" name="user_name" value="<? echo $user_name; ?>" size="40"></td>
                                    <td>Username to access Vesuvius (only alphanumericals, length: Max - 15, Min - 5)</td>
                                </tr>
                                <tr>
                                    <td <? if ($error_list !== false && in_array("password", $error_list)) echo $err_style; ?> >Password</td>
                                    <td><input type="password" name="password" value="" size="40"></td>
                                    <td>Password to access Vesuvius (only alphanumericals, length: Max - 15, Min - 5)</td>
                                </tr>
                                <tr>
                                    <td <? if ($error_list !== false && in_array("password_re", $error_list)) echo $err_style; ?> >Re-enter Password</td>
                                    <td><input type="password" name="password_re" value="" size="40"></td>
                                    <td>Enter the password again to verify</td>
                                </tr>						 
                                <tr>
                                    <td></td>
                                    <td>
                                        <input class="styleTehButton" style="margin: 10px;" type="submit" name="submit" value="   Submit   ">
                                        <? if ($portable_conf_data !== false) { ?>
                                            <input class="styleTehButton" style="margin: 10px;" type="submit" name="skip" value="   Skip   ">
                                        <? } ?>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div><!-- /content -->
            </div><!-- /wrapper -->		
            <? shn_theme_footer(); // Print HTML Footer  ?>
        </div>
    </body>
    </html>
    <?php
}

function shn_theme_body_block() {
    ?><body>
        <div id="container">
            <div id="header" class="clearfix">
                <div id="leftHeaderLogo"><a href="#"><img id="leftHeaderLogoImg" src="theme/img/pl.png" alt="People Locator Logo"></a><sup id="suplogo" style="font-size: 120%; color: #34689A;">&#0153;</sup>
                </div>
                <div id="rightHeaderLogo"><a href="#"><img src="theme/img/NLMlogoSmall.gif" alt="United States National Library of Medicine Logo"></a>
                </div>
            </div>
            <div id="headerText">
                <h1>Sahana Vesuvius<sup>&#0153;</sup></h1>
                <h3>&nbsp;</h3>
                <h4>U.S. National Library of Medicine</h4>
                <h4>Lister Hill National Center for Biomedical Communications</h4>
            </div>
            <div id="wrapper" class="clearfix">

                <!-- Left hand side menus & login form -->
                <div id="content" class="clearfix">
                    <div style="padding: 0px 0px 0px 36px;">
                        <h3>Vesuvius Portable has not been registered yet. Please try again later.</h3>				
                    </div>
                </div><!-- /content -->
            </div><!-- /wrapper -->		
            <? shn_theme_footer(); // Print HTML Footer  ?>
        </div>
    </body>
    </html>
    <?php
}

function shn_theme_body_view_owner() {
    $portable_conf_data = get_portable_conf_data();
    if ($portable_conf_data !== false) {
        $owner_name = $portable_conf_data['owner_name'];
        $portable_desc = $portable_conf_data['portable_desc'];
        $organization = $portable_conf_data['organization'];
        $parent_base_uuid = $portable_conf_data['parent_base_uuid'];
        $owner_email = $portable_conf_data['owner_email'];
        $portable_base_uuid = $portable_conf_data['portable_base_uuid'];
    }
    ?><body>
        <div id="container">
            <div id="header" class="clearfix">
                <div id="leftHeaderLogo"><a href="#"><img id="leftHeaderLogoImg" src="theme/img/pl.png" alt="People Locator Logo"></a><sup id="suplogo" style="font-size: 120%; color: #34689A;">&#0153;</sup>
                </div>
                <div id="rightHeaderLogo"><a href="#"><img src="theme/img/NLMlogoSmall.gif" alt="United States National Library of Medicine Logo"></a>
                </div>
            </div>
            <div id="headerText">
                <h1>Sahana Vesuvius<sup>&#0153;</sup></h1>
                <h3>&nbsp;</h3>
                <h4>U.S. National Library of Medicine</h4>
                <h4>Lister Hill National Center for Biomedical Communications</h4>
            </div>
            <div id="wrapper" class="clearfix">

                <!-- Left hand side menus & login form -->
                <div id="content" class="clearfix">
                    <div style="padding: 0px 0px 0px 36px;">
                        <? if ($portable_conf_data !== false) { ?>
                            <h3>Vesuvius Portable Owner Details</h3>
                            <div style="margin-top: 50px;">					
                                <table id="owner_view" >
                                    <tr>
                                        <td>Owner's name</td>
                                        <td><b><? echo $owner_name; ?></b></td>							
                                    </tr>
                                    <tr>
                                        <td>Description</td>
                                        <td><b><? echo $portable_desc; ?></b></td>							
                                    </tr>
                                    <tr>
                                        <td>Organization Name</td>
                                        <td><b><? echo $organization; ?></b></td>							
                                    </tr>
                                    <tr>
                                        <td>Parent instance base_uuid</td>
                                        <td><b><? echo $parent_base_uuid; ?></b></td>							
                                    </tr>
                                    <tr>
                                        <td>Owner's email</td>
                                        <td><b><? echo $owner_email; ?></b></td>							
                                    </tr>
                                    <tr>
                                        <td>Portable instance base_uuid</td>
                                        <td><b><? echo $portable_base_uuid; ?></b></td>							
                                    </tr>
                                    <tr>
                                        <td></td>							
                                    </tr>
                                </table>					
                            </div>
                        <? } else { ?>
                            <h3>Vesuvius Portable instance has not been registered.</h3>
                        <? } ?>
                    </div>

                </div><!-- /content -->
            </div><!-- /wrapper -->		
            <? shn_theme_footer(); // Print HTML Footer   ?>
        </div>
    </body>
    </html>
    <?
}

function shn_theme_body_error() {
    global $global;
    ?><body>
        <div id="container">
            <div id="header" class="clearfix">
                <div id="leftHeaderLogo"><a href="#"><img id="leftHeaderLogoImg" src="theme/img/pl.png" alt="People Locator Logo"></a><sup id="suplogo" style="font-size: 120%; color: #34689A;">&#0153;</sup>
                </div>
                <div id="rightHeaderLogo"><a href="#"><img src="theme/img/NLMlogoSmall.gif" alt="United States National Library of Medicine Logo"></a>
                </div>
            </div>
            <div id="headerText">
                <h1>Sahana Vesuvius<sup>&#0153;</sup></h1>
                <h3>&nbsp;</h3>
                <h4>U.S. National Library of Medicine</h4>
                <h4>Lister Hill National Center for Biomedical Communications</h4>
            </div>
            <div id="wrapper" class="clearfix">

                <!-- Left hand side menus & login form -->
                <div id="content" class="clearfix">
                    <div style="padding: 20px 0px 0px 36px;">
                        <h3>Failed to initialize Vesuvius. The following errors were encountered.</h3>	
                        <br/>
                        <?
                        foreach ($global['error_list'] as $err) {
                            echo "<li>" . $err . "</li>";
                        }
                        ?>
                    </div>
                </div><!-- /content -->			
            </div><!-- /wrapper -->		

            <? shn_theme_footer(); // Print HTML Footer  ?>
        </div>
    </body>
    </html>
    <?php
}
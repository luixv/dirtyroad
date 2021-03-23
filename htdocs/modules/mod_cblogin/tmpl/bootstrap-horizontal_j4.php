<?php
/**
* Community Builder (TM)
* @version $Id: $
* @package CommunityBuilder
* @copyright (C) 2004-2021 www.joomlapolis.com / Lightning MultiCom SA - and its licensors, all rights reserved
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

use CBLib\Language\CBTxt;
use Joomla\CMS\Language\Text;

$app->getDocument()->getWebAssetManager()
	->useScript('core')
	->useScript('keepalive')
	->useScript('field.passwordview');

Text::script('JSHOWPASSWORD');
Text::script('JHIDEPASSWORD');
?>
<?php echo modCBLoginHelper::getPlugins( $params, $type, 'beforeForm', 'span', 1 ); ?>
<form action="<?php echo $_CB_framework->viewUrl( 'login', true, null, 'html', $secureForm ); ?>" method="post" id="login-form-<?php echo $module->id; ?>" class="mod-login form-inline cbLoginForm">
	<input type="hidden" name="option" value="com_comprofiler" />
	<input type="hidden" name="view" value="login" />
	<input type="hidden" name="op2" value="login" />
	<input type="hidden" name="return" value="B:<?php echo $loginReturnUrl; ?>" />
	<input type="hidden" name="message" value="<?php echo (int) $params->get( 'login_message', 0 ); ?>" />
	<input type="hidden" name="loginfrom" value="<?php echo htmlspecialchars( ( defined( '_UE_LOGIN_FROM' ) ? _UE_LOGIN_FROM : 'loginmodule' ) ); ?>" />
	<?php echo cbGetSpoofInputTag( 'login' ); ?>
	<?php echo modCBLoginHelper::getPlugins( $params, $type, 'start', 'span', 1 ); ?>
	<?php if ( $preLogintText ) { ?>
		<div class="d-inline-block mr-2 mod-login__pretext pretext <?php echo htmlspecialchars( $templateClass ); ?>"><?php echo $preLogintText; ?></div>
	<?php } ?>
	<?php echo modCBLoginHelper::getPlugins( $params, $type, 'almostStart', 'span', 1 ); ?>
	<?php if ( $loginMethod != 4 ) { ?>
		<div class="d-inline-block mod-login__userdata userdata">
			<div class="d-inline-flex mod-login__username form-group">
				<?php if ( in_array( $showUsernameLabel, array( 1, 2, 3, 5 ) ) ) { ?>
					<?php if ( in_array( $showUsernameLabel, array( 2, 3, 5 ) ) ) { ?>
						<?php if ( $showUsernameLabel == 3 ) { ?>
							<label for="modlgn-username-<?php echo $module->id; ?>"><?php echo htmlspecialchars( $userNameText ); ?></label>
						<?php } ?>
						<div class="input-group">
							<input id="modlgn-username-<?php echo $module->id; ?>" type="text" name="username" class="<?php echo ( $styleUsername ? htmlspecialchars( $styleUsername ) : 'form-control' ); ?>" size="<?php echo $usernameInputLength; ?>" autocomplete="username"<?php echo ( in_array( $showUsernameLabel, array( 4, 5 ) ) ? ' placeholder="' . htmlspecialchars( $userNameText ) . '"' : null ); ?>>
							<span class="input-group-append">
								<?php if ( $showUsernameLabel != 3 ) { ?>
									<label for="modlgn-username-<?php echo $module->id; ?>" class="sr-only"><?php echo htmlspecialchars( $userNameText ); ?></label>
								<?php } ?>
								<span class="input-group-text" title="<?php echo htmlspecialchars( $userNameText ); ?>">
									<span class="fas fa-user fa-fw cbModuleUsernameIcon" aria-hidden="true"></span>
								</span>
							</span>
						</div>
					<?php } else { ?>
						<?php if ( in_array( $showUsernameLabel, array( 1, 3 ) ) ) { ?>
							<label for="modlgn-username-<?php echo $module->id; ?>"><?php echo htmlspecialchars( $userNameText ); ?></label>
						<?php } ?>
						<input id="modlgn-username-<?php echo $module->id; ?>" type="text" name="username" class="<?php echo ( $styleUsername ? htmlspecialchars( $styleUsername ) : 'form-control' ); ?>" size="<?php echo $usernameInputLength; ?>" autocomplete="username"<?php echo ( in_array( $showUsernameLabel, array( 4, 5 ) ) ? ' placeholder="' . htmlspecialchars( $userNameText ) . '"' : null ); ?>>
					<?php } ?>
				<?php } else { ?>
					<input id="modlgn-username-<?php echo $module->id; ?>" type="text" name="username" class="<?php echo ( $styleUsername ? htmlspecialchars( $styleUsername ) : 'form-control' ); ?>" size="<?php echo $usernameInputLength; ?>" autocomplete="username"<?php echo ( in_array( $showUsernameLabel, array( 4, 5 ) ) ? ' placeholder="' . htmlspecialchars( $userNameText ) . '"' : null ); ?>>
				<?php } ?>
			</div>
			<div class="d-inline-flex ml-2 mod-login__password form-group">
				<?php if ( in_array( $showPasswordLabel, array( 1, 2, 3, 5 ) ) ) { ?>
					<?php if ( in_array( $showPasswordLabel, array( 2, 3, 5 ) ) ) { ?>
						<?php if ( $showPasswordLabel == 3 ) { ?>
							<label for="modlgn-passwd-<?php echo $module->id; ?>"><?php echo htmlspecialchars( CBTxt::T( 'Password' ) ); ?></label>
						<?php } ?>
						<div class="input-group">
							<input id="modlgn-passwd-<?php echo $module->id; ?>" type="password" name="passwd" autocomplete="current-password" class="<?php echo ( $stylePassword ? htmlspecialchars( $stylePassword ) : 'form-control' ); ?>" size="<?php echo $passwordInputLength; ?>"<?php echo ( in_array( $showPasswordLabel, array( 4, 5 ) ) ? ' placeholder="' . htmlspecialchars( CBTxt::T( 'Password' ) ) . '"' : null ); ?>>
							<span class="input-group-append">
								<?php if ( $showPasswordLabel != 3 ) { ?>
									<label for="modlgn-passwd-<?php echo $module->id; ?>" class="sr-only"><?php echo htmlspecialchars( CBTxt::T( 'Password' ) ); ?></label>
								<?php } ?>
								<button type="button" class="btn btn-secondary input-password-toggle">
									<span class="fas fa-eye fa-fw" aria-hidden="true"></span>
									<span class="sr-only"><?php echo CBTxt::T( 'Show Password' ); ?></span>
								</button>
							</span>
						</div>
					<?php } else { ?>
						<?php if ( in_array( $showPasswordLabel, array( 1, 3 ) ) ) { ?>
							<label for="modlgn-passwd-<?php echo $module->id; ?>"><?php echo htmlspecialchars( CBTxt::T( 'Password' ) ); ?></label>
						<?php } ?>
						<input id="modlgn-passwd-<?php echo $module->id; ?>" type="password" name="passwd" autocomplete="current-password" class="<?php echo ( $stylePassword ? htmlspecialchars( $stylePassword ) : 'form-control' ); ?>" size="<?php echo $passwordInputLength; ?>"<?php echo ( in_array( $showPasswordLabel, array( 4, 5 ) ) ? ' placeholder="' . htmlspecialchars( CBTxt::T( 'Password' ) ) . '"' : null ); ?>>
					<?php } ?>
				<?php } else { ?>
					<input id="modlgn-passwd-<?php echo $module->id; ?>" type="password" name="passwd" autocomplete="current-password" class="<?php echo ( $stylePassword ? htmlspecialchars( $stylePassword ) : 'form-control' ); ?>" size="<?php echo $passwordInputLength; ?>"<?php echo ( in_array( $showPasswordLabel, array( 4, 5 ) ) ? ' placeholder="' . htmlspecialchars( CBTxt::T( 'Password' ) ) . '"' : null ); ?>>
				<?php } ?>
			</div>
			<?php if ( count( $twoFactorMethods ) > 1 ) { ?>
				<div class="d-inline-flex ml-2 mod-login__twofactor form-group">
					<?php if ( in_array( $showSecretKeyLabel, array( 1, 2, 3, 5 ) ) ) { ?>
						<?php if ( in_array( $showSecretKeyLabel, array( 2, 3, 5 ) ) ) { ?>
							<?php if ( $showSecretKeyLabel == 3 ) { ?>
								<label for="modlgn-secretkey-<?php echo $module->id; ?>"><?php echo htmlspecialchars( CBTxt::T( 'Secret Key' ) ); ?></label>
							<?php } ?>
							<div class="input-group">
								<span class="input-group-prepend" title="<?php echo htmlspecialchars( CBTxt::T( 'Secret Key' ) ); ?>">
									<span class="input-group-text">
										<span class="icon-star" aria-hidden="true"></span>
									</span>
									<?php if ( $showSecretKeyLabel != 3 ) { ?>
										<label for="modlgn-secretkey-<?php echo $module->id; ?>" class="sr-only"><?php echo htmlspecialchars( CBTxt::T( 'Secret Key' ) ); ?></label>
									<?php } ?>
								</span>
								<input id="modlgn-secretkey-<?php echo $module->id; ?>" autocomplete="one-time-code" type="text" name="secretkey" class="<?php echo ( $styleSecretKey ? htmlspecialchars( $styleSecretKey ) : 'form-control' ); ?>" size="<?php echo $secretKeyInputLength; ?>"<?php echo ( in_array( $showSecretKeyLabel, array( 4, 5 ) ) ? ' placeholder="' . htmlspecialchars( CBTxt::T( 'Secret Key' ) ) . '"' : null ); ?>>
								<span class="input-group-append" title="<?php echo htmlspecialchars( CBTxt::T( 'If you have enabled two factor authentication in your user account please enter your secret key. If you do not know what this means, you can leave this field blank.' ) ); ?>">
									<span class="input-group-text">
										<span class="icon-question icon-fw" aria-hidden="true"></span>
									</span>
								</span>
							</div>
						<?php } else { ?>
							<?php if ( in_array( $showSecretKeyLabel, array( 1, 3 ) ) ) { ?>
								<label for="modlgn-secretkey-<?php echo $module->id; ?>"><?php echo htmlspecialchars( CBTxt::T( 'Secret Key' ) ); ?></label>
							<?php } ?>
							<div class="input-group">
								<input id="modlgn-secretkey-<?php echo $module->id; ?>" autocomplete="one-time-code" type="text" name="secretkey" class="<?php echo ( $styleSecretKey ? htmlspecialchars( $styleSecretKey ) : 'form-control' ); ?>" size="<?php echo $secretKeyInputLength; ?>"<?php echo ( in_array( $showSecretKeyLabel, array( 4, 5 ) ) ? ' placeholder="' . htmlspecialchars( CBTxt::T( 'Secret Key' ) ) . '"' : null ); ?>>
								<span class="input-group-append" title="<?php echo htmlspecialchars( CBTxt::T( 'If you have enabled two factor authentication in your user account please enter your secret key. If you do not know what this means, you can leave this field blank.' ) ); ?>">
									<span class="input-group-text">
										<span class="icon-question icon-fw" aria-hidden="true"></span>
									</span>
								</span>
							</div>
						<?php } ?>
					<?php } else { ?>
						<div class="input-group">
							<input id="modlgn-secretkey-<?php echo $module->id; ?>" autocomplete="one-time-code" type="text" name="secretkey" class="<?php echo ( $styleSecretKey ? htmlspecialchars( $styleSecretKey ) : 'form-control' ); ?>" size="<?php echo $secretKeyInputLength; ?>"<?php echo ( in_array( $showSecretKeyLabel, array( 4, 5 ) ) ? ' placeholder="' . htmlspecialchars( CBTxt::T( 'Secret Key' ) ) . '"' : null ); ?>>
							<span class="input-group-append" title="<?php echo htmlspecialchars( CBTxt::T( 'If you have enabled two factor authentication in your user account please enter your secret key. If you do not know what this means, you can leave this field blank.' ) ); ?>">
								<span class="input-group-text">
									<span class="icon-question icon-fw" aria-hidden="true"></span>
								</span>
							</span>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
			<?php if ( in_array( $showRememberMe, array( 1, 3 ) ) ) { ?>
				<div class="d-inline-flex ml-2 mod-login__remember form-group">
					<div id="form-login-remember-<?php echo $module->id; ?>" class="form-check">
						<label class="form-check-label">
							<input type="checkbox" name="remember" class="form-check-input" value="yes"<?php echo ( $showRememberMe == 3 ? ' checked' : null ); ?>>
							<?php echo htmlspecialchars( CBTxt::T( 'Remember Me' ) ); ?>
						</label>
					</div>
				</div>
			<?php } elseif ( $showRememberMe == 2 ) { ?>
				<input type="hidden" name="remember" value="yes">
			<?php } ?>
			<?php echo modCBLoginHelper::getPlugins( $params, $type, 'beforeButton', 'span', 1, null, '&nbsp;' ); ?>
			<div class="d-inline-flex ml-2 mod-login__submit form-group">
				<button type="submit" name="Submit" class="<?php echo ( $styleLogin ? htmlspecialchars( $styleLogin ) : 'btn btn-primary login-button' ); ?>"<?php echo $buttonStyle; ?>>
					<?php if ( in_array( $showButton, array( 1, 2, 3 ) ) ) { ?>
						<span class="<?php echo htmlspecialchars( $templateClass ); ?>">
							<span class="cbModuleLoginIcon fa fa-sign-in" title="<?php echo htmlspecialchars( CBTxt::T( 'Log in' ) ); ?>"></span>
						</span>
					<?php } ?>
					<?php if ( in_array( $showButton, array( 0, 1, 4 ) ) ) { ?>
						<?php echo htmlspecialchars( CBTxt::T( 'Log in' ) ); ?>
					<?php } ?>
				</button>
			</div>
			<?php echo modCBLoginHelper::getPlugins( $params, $type, 'afterButton', 'span', 1, null, '&nbsp;' ); ?>
		</div>
	<?php } else { ?>
		<?php echo modCBLoginHelper::getPlugins( $params, $type, 'beforeButton', 'span', 1, null, '&nbsp;' ); ?>
		<?php echo modCBLoginHelper::getPlugins( $params, $type, 'afterButton', 'span', 1, null, '&nbsp;' ); ?>
	<?php } ?>
	<?php if ( $showForgotLogin || $showRegister ) { ?>
		<div class="d-inline-block mod-login__options list-unstyled">
			<?php if ( $showForgotLogin ) { ?>
				<div class="d-inline-block ml-2 form-login-options-forgot">
					<a href="<?php echo $_CB_framework->viewUrl( 'lostpassword', true, null, 'html', $secureForm ); ?>"<?php echo ( $styleForgotLogin ? ' class="' . htmlspecialchars( $styleForgotLogin ) . '"' : null ); ?>>
						<?php if ( in_array( $showForgotLogin, array( 2, 3 ) ) ) { ?>
							<span class="<?php echo htmlspecialchars( $templateClass ); ?>">
								<span class="cbModuleForgotLoginIcon fa fa-unlock-alt" title="<?php echo htmlspecialchars( CBTxt::T( 'Forgot Login?' ) ); ?>"></span>
							</span>
						<?php } ?>
						<?php if ( in_array( $showForgotLogin, array( 1, 3 ) ) ) { ?>
							<?php echo CBTxt::T( 'Forgot Login?' ); ?>
						<?php } ?>
					</a>
				</div>
			<?php } ?>
			<?php if ( $showRegister ) { ?>
				<div class="d-inline-block ml-2 form-login-options-register">
					<a href="<?php echo $_CB_framework->viewUrl( 'registers', true, null, 'html', $secureForm ); ?>"<?php echo ( $styleRegister ? ' class="' . htmlspecialchars( $styleRegister ) . '"' : null ); ?>>
						<?php if ( in_array( $params->get( 'show_newaccount', 1 ), array( 2, 3 ) ) ) { ?>
							<span class="<?php echo htmlspecialchars( $templateClass ); ?>">
								<span class="cbModuleRegisterIcon fa fa-edit" title="<?php echo htmlspecialchars( CBTxt::T( 'UE_REGISTER', 'Sign up' ) ); ?>"></span>
							</span>
						<?php } ?>
						<?php if ( in_array( $params->get( 'show_newaccount', 1 ), array( 1, 3 ) ) ) { ?>
							<?php echo CBTxt::T( 'UE_REGISTER', 'Sign up' ); ?>
						<?php } ?>
					</a>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
	<?php echo modCBLoginHelper::getPlugins( $params, $type, 'almostEnd', 'span', 1 ); ?>
	<?php if ( $postLoginText ) { ?>
		<div class="d-inline-block ml-2 mod-login__posttext posttext <?php echo htmlspecialchars( $templateClass ); ?>"><?php echo $postLoginText; ?></div>
	<?php } ?>
	<?php echo modCBLoginHelper::getPlugins( $params, $type, 'end', 'span', 1 ); ?>
</form>
<?php echo modCBLoginHelper::getPlugins( $params, $type, 'afterForm', 'span', 1 ); ?>
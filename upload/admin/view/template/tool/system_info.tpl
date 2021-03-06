<?php echo $header; ?>

<script type="text/javascript" src="view/javascript/systeminfo/idTabs.min.js?v=2.2"></script>

<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	
	<div class="container">
		<div id="et-header">
			<div class="productTitle">
				<img src="view/image/systeminfo/system_information.png" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" />
			</div>
			<div class="productVersion">v.<?php echo $product_version; ?></div>
			<div class="echoSite"><a href="http://www.echothemes.com" title="EchoThemes" target="_blank">www.echothemes.com</a></div>
		</div>

		<div id="et-middle">
			<div id="et-sidenav">
				<ul>
					<li><a href="#systemInfo"><?php echo $menu_system_info; ?></a></li>
					<li><a href="#serverSetting"><?php echo $menu_php_settings; ?></a></li>
					<li><a href="#dirPermission"><?php echo $menu_dir_permission; ?></a></li>
					<li><a href="#about"><?php echo $menu_about; ?></a></li>
				</ul>
			</div>
			<div id="et-content">
				<div id="systemInfo">
					<table class="et-table et-info">
						<tr>
							<td width="175px"><?php echo $text_system_info; ?></td>
							<td><?php echo $text_value; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_oc_version; ?></td>
							<td class="left">: <?php echo sprintf(VERSION); ?></td>
						</tr>
						<tr>
							<td><?php echo $text_operating_sys; ?></td>
							<td class="left">: <?php echo php_uname(); ?></td>
						</tr>
						<tr>
							<td><?php echo $text_web_server; ?></td>
							<td class="left">: <?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_web_host; ?></td>
							<td class="left">: <?php echo $_SERVER['HTTP_HOST']; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_mysql_version; ?></td>
							<td class="left">: <?php printf(mysql_get_server_info()); ?></td>
						</tr>
						<tr>
							<td><?php echo $text_mysql_host; ?></td>
							<td class="left">: <?php printf(mysql_get_host_info()); ?></td>
						</tr>
						<tr>
							<td><?php echo $text_root_path; ?></td>
							<td class="left">: <?php echo $_SERVER["DOCUMENT_ROOT"]; ?></td>
						</tr>
						<tr>
							<td><?php echo 'Full phpInfo'; ?></td>
							<td class="left">: <a href="<?php echo $this->url->link('tool/system_info/phpInfo', 'token=' . $token, 'SSL'); ?>" target="_blank">phpInfo</a></td>
						</tr>
					</table>
				</div>
		
				<div id="serverSetting">
					<table class="et-table et-info">
						<tr>
							<td width="35%"><?php echo $text_setting; ?></td>
							<td width="25%"><?php echo $text_required; ?></td>
							<td width="25%"><?php echo $text_server; ?></td>
							<td width="15%"><?php echo $text_status; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_php_version; ?></td>
							<td><?php echo $text_req_php_version; ?></td>
							<td><?php echo phpversion(); ?></td>
							<td><?php echo (phpversion() >= '5.0') ? '<img src="view/image/systeminfo/good.png" alt="Good" />' : '<img src="view/image/systeminfo/bad.png" alt="Bad" />'; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_safe_mode; ?></td>
							<td><?php echo $text_off; ?></td>
							<td><?php echo (ini_get('safe_mode')) ? '<span class="bad"><blink>'.$text_on.'</blink></span>' : $text_off; ?></td>
							<td><?php echo (!ini_get('sefe_mode')) ? '<img src="view/image/systeminfo/good.png" alt="Good" />' : '<img src="view/image/systeminfo/bad.png" alt="Bad" />'; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_register_globals; ?></td>
							<td><?php echo $text_off; ?></td>
							<td><?php echo (ini_get('register_globals')) ? '<span class="bad"><blink>'.$text_on.'</blink></span>' : $text_off; ?></td>
							<td><?php echo (!ini_get('register_globals')) ? '<img src="view/image/systeminfo/good.png" alt="Good" />' : '<img src="view/image/systeminfo/bad.png" alt="Bad" />'; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_magic_quotes;?></td>
							<td><?php echo $text_off; ?></td>
							<td><?php echo (ini_get('magic_quotes_gpc') || get_magic_quotes_gpc()) ? '<span class="bad"><blink>'.$text_on.'</blink></span>' : $text_off; ?></td>
							<td><?php echo (!ini_get('magic_quotes_gpc') || !get_magic_quotes_gpc()) ? '<img src="view/image/systeminfo/good.png" alt="Good" />' : '<img src="view/image/systeminfo/bad.png" alt="Bad" />'; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_session_start;?></td>
							<td><?php echo $text_off; ?></td>
							<td><?php echo (ini_get('session_auto_start')) ? '<span class="bad"><blink>'.$text_on.'</blink></span>' : $text_off; ?></td>
							<td><?php echo (!ini_get('session_auto_start')) ? '<img src="view/image/systeminfo/good.png" alt="Good" />' : '<img src="view/image/systeminfo/bad.png" alt="Bad" />'; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_allow_url_fopen;?></td>
							<td><?php echo $text_on; ?></td>
							<td><?php echo (ini_get('allow_url_fopen')) ? $text_on : '<span class="bad"><blink>'.$text_off.'</blink></span>'; ?></td>
							<td><?php echo (ini_get('allow_url_fopen')) ? '<img src="view/image/systeminfo/good.png" alt="Good" />' : '<img src="view/image/systeminfo/bad.png" alt="Bad" />'; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_file_uploads;?></td>
							<td><?php echo $text_on; ?></td>
							<td><?php echo (ini_get('file_uploads')) ? $text_on : '<span class="bad"><blink>'.$text_off.'</blink></span>'; ?></td>
							<td><?php echo (ini_get('file_uploads')) ? '<img src="view/image/systeminfo/good.png" alt="Good" />' : '<img src="view/image/systeminfo/bad.png" alt="Bad" />'; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_session_cookies;?></td>
							<td><?php echo $text_on; ?></td>
							<td><?php echo (ini_get('session.use_cookies')) ? $text_on : $text_off; ?></td>
							<td><?php echo (ini_get('session.use_cookies')) ? '<img src="view/image/systeminfo/good.png" alt="Good" />' : '<img src="view/image/systeminfo/bad.png" alt="Bad" />'; ?></td>
						</tr>
					</table>
					<table class="et-table et-info">
						<tr>
							<td width="35%"><b><?php echo $text_extenisons;?></b></td>
							<td width="25%"><b><?php echo $text_required;?></b></td>
							<td width="25%"><b><?php echo $text_current;?></b></td>
							<td width="15%"><b><?php echo $text_status; ?></b></td>
						</tr>
						<tr>
							<td><?php echo $text_mysql; ?></td>
							<td><?php echo $text_on; ?></td>
							<td><?php echo extension_loaded('mysql') ? 'On' : '<span class="bad"><blink>'.$text_off.'</blink></span>'; ?></td>
							<td><?php echo extension_loaded('mysql') ? '<img src="view/image/systeminfo/good.png" alt="Good" />' : '<img src="view/image/systeminfo/bad.png" alt="Bad" />'; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_gd; ?></td>
							<td><?php echo $text_on; ?></td>
							<td><?php echo extension_loaded('gd') ? 'On' : '<span class="bad"><blink>'.$text_off.'</blink></span>'; ?></td>
							<td><?php echo extension_loaded('gd') ? '<img src="view/image/systeminfo/good.png" alt="Good" />' : '<img src="view/image/systeminfo/bad.png" alt="Bad" />'; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_curl; ?></td>
							<td><?php echo $text_on; ?></td>
							<td><?php echo extension_loaded('curl') ? 'On' : '<span class="bad"><blink>'.$text_off.'</blink></span>'; ?></td>
							<td><?php echo extension_loaded('curl') ? '<img src="view/image/systeminfo/good.png" alt="Good" />' : '<img src="view/image/systeminfo/bad.png" alt="Bad" />'; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_fsock; ?></td>
							<td><?php echo $text_on; ?></td>
							<td><?php echo extension_loaded('sockets') ? 'On' : '<span class="bad"><blink>'.$text_off.'</blink></span>'; ?></td>
							<td><?php echo extension_loaded('sockets') ? '<img src="view/image/systeminfo/good.png" alt="Good" />' : '<img src="view/image/systeminfo/bad.png" alt="Bad" />'; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_zip; ?></td>
							<td><?php echo $text_on; ?></td>
							<td><?php echo extension_loaded('zlib') ? 'On' : '<span class="bad"><blink>'.$text_off.'</blink></span>'; ?></td>
							<td><?php echo extension_loaded('zlib') ? '<img src="view/image/systeminfo/good.png" alt="Good" />' : '<img src="view/image/systeminfo/bad.png" alt="Bad" />'; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_xml; ?></td>
							<td><?php echo $text_on; ?></td>
							<td><?php echo extension_loaded('xml') ? 'On' : '<span class="bad"><blink>'.$text_off.'</blink></span>'; ?></td>
							<td><?php echo extension_loaded('xml') ? '<img src="view/image/systeminfo/good.png" alt="Good" />' : '<img src="view/image/systeminfo/bad.png" alt="Bad" />'; ?></td>
						</tr>
					</table>
				</div> 
				
				<div id="dirPermission">
					<table class="et-table et-permission">
						<tr>
							<td><?php echo $text_directories; ?></td>
							<td width="110px"><?php echo $text_permission; ?></td>
							<td width="110px"><?php echo $text_status; ?></td>
						</tr>
						<tr>
							<td><?php echo str_replace($_SERVER['DOCUMENT_ROOT'],'',(str_replace('\\','/',DIR_IMAGE))); ?></td>
							<td><?php echo substr(sprintf('%o', fileperms(DIR_IMAGE)), -3); ?></td>
							<td><?php echo is_writable(DIR_IMAGE) ? '<span class="good">Writable</span>' : '<span class="bad"><blink>Unwritable</blink></span>'; ?></td>
						</tr>
						<tr>
							<td><?php echo str_replace($_SERVER['DOCUMENT_ROOT'],'',(str_replace('\\','/',(DIR_IMAGE . 'data').'/'))); ?></td>
							<td><?php echo substr(sprintf('%o', fileperms(DIR_IMAGE . 'data')), -3); ?></td>
							<td><?php echo is_writable(DIR_IMAGE . 'data') ? '<span class="good">Writable</span>' : '<span class="bad"><blink>Unwritable</blink></span>'; ?></td>
						</tr>
						<tr>
							<td><?php echo str_replace($_SERVER['DOCUMENT_ROOT'],'',(str_replace('\\','/',(DIR_IMAGE . 'cache').'/'))); ?></td>
							<td><?php echo substr(sprintf('%o', fileperms(DIR_IMAGE . 'cache')), -3); ?></td>
							<td><?php echo is_writable(DIR_IMAGE . 'cache') ? '<span class="good">Writable</span>' : '<span class="bad"><blink>Unwritable</blink></span>'; ?></td>
						</tr>
						<tr>
							<td><?php echo str_replace($_SERVER['DOCUMENT_ROOT'],'',(str_replace('\\','/',DIR_DOWNLOAD))); ?></td>
							<td><?php echo substr(sprintf('%o', fileperms(DIR_DOWNLOAD)), -3); ?></td>
							<td><?php echo is_writable(DIR_DOWNLOAD) ? '<span class="good">Writable</span>' : '<span class="bad"><blink>Unwritable</blink></span>'; ?></td>
						</tr>
						<tr>
							<td><?php echo str_replace($_SERVER['DOCUMENT_ROOT'],'',(str_replace('\\','/',DIR_CACHE))); ?></td>
							<td><?php echo substr(sprintf('%o', fileperms(DIR_CACHE)), -3); ?></td>
							<td><?php echo is_writable(DIR_CACHE) ? '<span class="good">Writable</span>' : '<span class="bad"><blink>Unwritable</blink></span>'; ?></td>
						</tr>
						<tr>
							<td><?php echo str_replace($_SERVER['DOCUMENT_ROOT'],'',(str_replace('\\','/',DIR_LOGS))); ?></td>
							<td><?php echo substr(sprintf('%o', fileperms(DIR_LOGS)), -3); ?></td>
							<td><?php echo is_writable(DIR_LOGS) ? '<span class="good">Writable</span>' : '<span class="bad"><blink>Unwritable</blink></span>'; ?></td>
						</tr>
						<tr>
							<td><?php echo '/vqmod/vqcache/'; ?></td>
							<td><?php echo substr(sprintf('%o', fileperms('../vqmod/vqcache')), -3); ?></td>
							<td><?php echo is_writable('../vqmod/vqcache') ? '<span class="good">Writable</span>' : '<span class="bad"><blink>Unwritable</blink></span>'; ?></td>
						</tr>
					</table>
					
					<table class="et-table et-permission">
						<tr>
							<td><?php echo $text_files; ?></td>
							<td width="110px"><?php echo $text_permission; ?></td>
							<td width="110px"><?php echo $text_status; ?></td>
						</tr>
						<tr>
							<?php $file_error_logs = DIR_LOGS . $this->config->get('config_error_filename'); ?>
							<td><?php echo str_replace($_SERVER['DOCUMENT_ROOT'],'',(str_replace('\\','/',$file_error_logs))); ?></td>
							<td><?php 
								if (file_exists($file_error_logs)) {
									echo substr(sprintf('%o', fileperms($file_error_logs)), -3);
								} else {
									echo $text_unavailable;
								}
							?></td>
							<td><?php 
								if (file_exists($file_error_logs)) {
									echo is_writable($file_error_logs) ? '<span class="good">Writable</span>' : '<span class="bad"><blink>Unwritable</blink></span>'; 
								} else {
									echo $text_unavailable;
								}
							?></td>
						</tr>
						<tr>
							<?php $file_vqmods_logs = '../vqmod/vqmod.log'; ?>
							<td><?php echo '/vqmod/vqmod.log'; ?></td>
							<td><?php 
								if (file_exists($file_vqmods_logs)) {
									echo substr(sprintf('%o', fileperms($file_vqmods_logs)), -3);
								} else {
									echo $text_unavailable;
								}
							?></td>
							<td><?php 
								if (file_exists($file_vqmods_logs)) {
									echo is_writable($file_vqmods_logs) ? '<span class="good">Writable</span>' : '<span class="bad"><blink>Unwritable</blink></span>'; 
								} else {
									echo $text_unavailable;
								}
							?></td>
						</tr>
					</table>
				</div>

				<div id="about">
					<table class="table-form">
						<tr>
							<td><?php echo $text_name; ?></td>
							<td>: <?php echo $heading_title; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_version; ?></td>
							<td>: <?php echo $product_version; ?> <span id="versionStatus"><a onclick="checkVersion();"><?php echo $text_check_version; ?></a></span></td>
						</tr>
						<tr>
							<td><?php echo $text_author; ?></td>
							<td>: EchoThemes</td>
						</tr>
						<tr>
							<td><?php echo $text_author_url; ?></td>
							<td>: <a href="http://www.echothemes.com" target="_blank">http://www.echothemes.com</a></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td></td>
						</tr>
						<tr>
							<td><?php echo $text_documentation; ?></td>
							<td>: <a href="http://www.echothemes.com/docs-system-information.html" target="_blank"><?php echo $heading_title . ' ' . $text_documentation; ?></a></td>
						</tr>
						<tr>
							<td><?php echo $text_support; ?></td>
							<td>: <a href="http://www.echothemes.com/tickets.html" target="_blank"><?php echo $text_ticket_support; ?></a></td>
						</tr>
					</table>
				</div>
			
			</div>
		</div>
		<div id="et-footer">
			System Information &copy; <?php if ((date('Y')) == '2011') { echo date('Y'); } else { echo '2011 - '.date('Y');};?> <a href="http://www.echothemes.com" target="_blank">EchoThemes</a>. All right reserved. <br />
			<a href="http://www.opencart.com" target="_blank">OpenCart</a> &copy; 2009-<?php echo date('Y');?> All Rights Reserved. <br /> Version <?php echo sprintf(VERSION); ?>
		</div>
	</div>
</div>

<script type="text/javascript" src="view/javascript/systeminfo/systeminfo.js"></script>
<script type="text/javascript"><!--
function checkVersion() {
	$.ajax({
		url: 'index.php?route=tool/system_info/version&token=<?php echo $token; ?>&product=SystemInformation',
		type: 'POST',
		dataType: 'json',
		beforeSend: function() {
			$('#versionStatus').addClass('loading');
		},
		success: function(data) {
			setTimeout(function(){
				$('#versionStatus').html(data).removeClass('loading');
			},750);
		}
	});
}
//--></script>
<?php echo $footer; ?>
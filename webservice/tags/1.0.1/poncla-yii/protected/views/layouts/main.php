<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css" />

	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico"/> 

	<title><?php echo PHtml::encode($this->pageTitle); ?></title>
</head>

<body>

	<div class="bodycontainer" id="page">
	
		<div id="mainmenu">
			<?php $this->widget('zii.widgets.CMenu', array(
				'items'=>MenuDefinitions::mainMenu()
			)); 
			?>
		</div><!-- mainmenu -->

		<?php echo $content; ?>
	
	</div><!-- page -->
	
	<div id="footer">
			Poncla &copy; <?php echo date('Y'); ?><br/>
			All Rights Reserved.<br/>
	</div><!-- footer -->

</body>
</html>
<?php 
/**
 * View for individual comments
 * @uses $data Comment model
 */

$story = $this->beginWidget('application.components.widgets.Story', array(
	'htmlOptions'=>array(
		'id'=>"comment-" . PHtml::encode($data->id),
		'class'=>PHtml::commentClass($data),
),
));?>
	<?php $story->beginAvatar(); ?>
		<span class="creator">
			<?php //author 
				$this->widget('application.components.widgets.UserLink', array(
				'userModel' => $data->creator,
			));  ?>
		</span>
	<?php $story->endAvatar(); ?>
	<?php $story->beginStoryContent(); ?>

	
			<div class="story-details">
				<?php echo Yii::app()->format->formatStyledText($data->content); ?>
			</div>
	
		<?php $story->beginControls() ?>
		<li>
			<?php if(isset($model)) : ?>
			<span><?php echo PHtml::encode(Yii::app()->format->formatDateTimeAsAgo(strtotime($data->created))); ?></span>
			<?php else: ?>
			<span class="created">
				<?php echo PHtml::link(
					PHtml::encode(Yii::app()->format->formatDateTimeAsAgo(strtotime($data->created))),
					array(Yii::app()->request->pathInfo, 
						'#' => 'comment-' . $data->id,
					)
				); ?>
			</span>
			<?php endif; ?>
		</li>
		<?php $story->endControls() ?>
	<?php $story->endStoryContent(); ?>
<?php $this->endWidget(); ?>
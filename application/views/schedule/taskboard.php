<? foreach ($task_board as $column) { ?>
<div class="column sortable">	
<? foreach ($column as $task) { ?>
	<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all<?if(!$task['completed']){?> todo<?}?><?if(!$task['completed'] && $task['start']<$this->date->now){?> expired<?}?>" event-id="<?=$task['id']?>">
		<div class="portlet-header ui-widget-header ui-corner-all ellipsis">
			<span class='ui-icon ui-icon-minusthick'></span>
			<?=$task['name']?>
		</div>
		<div class="portlet-content"><?=str_getSummary($task['content'],60)?>
<?	if(isset($task['project'])){?>
			<hr /><span class="project">事务：<?=$task['project_name']?></span>
<?}?>
		</div>
	</div>
<? } ?>
</div>
<? } ?>
<div class="column sortable"></div>
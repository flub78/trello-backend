echo "Trello-backend code generation";

rem tpl -t boards -tp templates\Model.php -o results\BoardModel.php --compare ..\app\Models\Board.php
rem tpl -t columns -tp templates\Model.php -o results\ColumnModel.php --compare ..\app\Models\Column.php
rem tpl -t tasks -tp templates\Model.php -o results\TaskdModel.php --compare ..\app\Models\Task.php
rem tpl -t task_comments -tp templates\Model.php -o results\TaskCommentModel.php --compare ..\app\Models\TaskComment.php
rem tpl -t checklists -tp templates\Model.php -o results\ChecklistModel.php --compare ..\app\Models\Checklist.php
rem tpl -t tag_colors -tp templates\Model.php -o results\TagColorModel.php --compare ..\app\Models\TagColor.php
rem tpl -t tags -tp templates\Model.php -o results\TagModel.php --compare ..\app\Models\Tag.php
rem tpl -t checklist_items -tp templates\Model.php -o results\ChecklistItemModel.php --compare ..\app\Models\ChecklistItem.php

tpl -t boards -tp templates\ApiController.php -o results\BoardController.php --compare ..\app\Http\Controllers\api\BoardController.php
tpl -t columns -tp templates\ApiController.php -o results\ColumnController.php --compare ..\app\Http\Controllers\api\ColumnController.php
tpl -t tasks -tp templates\ApiController.php -o results\TaskController.php --compare ..\app\Http\Controllers\api\TaskController.php
tpl -t task_comments -tp templates\ApiController.php -o results\TaskCommentController.php --compare ..\app\Http\Controllers\api\TaskCommentController.php
tpl -t checklists -tp templates\ApiController.php -o results\ChecklistController.php --compare ..\app\Http\Controllers\api\ChecklistController.php
tpl -t tag_colors -tp templates\ApiController.php -o results\TagColorController.php --compare ..\app\Http\Controllers\api\TagColorController.php
tpl -t tags -tp templates\ApiController.php -o results\TagController.php --compare ..\app\Http\Controllers\api\TagController.php
tpl -t checklist_items -tp templates\ApiController.php -o results\ChecklistItemController.php --compare ..\app\Http\Controllers\api\ChecklistItemController.php
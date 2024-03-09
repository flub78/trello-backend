echo "Trello-backend code generation";

rem tpl -t boards -tp templates\Model.php -o results\BoardModel.php --compare ..\app\Models\Board.php

tpl -t boards -tp templates\ApiController.php -o results\BoardController.php --compare ..\app\Http\Controllers\api\BoardController.php
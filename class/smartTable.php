
<?php
////для вывода элементов в таблицу
class smartTable{
  private $rez;//здесь будет собираться код таблицы

  public function __construct(){
      //формирует заголовок таблицы
      $this->rez = '<table class="table table-striped">
                     <thead>
                       <tr>';

      $args = func_get_args();
      foreach ($args as $arg) {
        $this->rez .= '<th scope="col">' . $arg . '</th>';
        }
      $this->rez .=	'</tr> </thead><tbody>';
  }
  public function insertrow(){
    //вставляет строку в таблицу
    $args = func_get_args();
    $this->rez .='<tr>';
    foreach ($args as $arg) {
      $this->rez .= '<td>' . $arg . '</td>';
      }
      $this->rez .='</tr>';
  }
  public function showtable(){
    $this->rez .='</tbody>
                  </table>';
                  echo $this->rez;
  }

  }
  ?>

<?php 
require 'db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To-Do List</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="main-section">
       <div class="add-section">
          <form action="app/add.php" method="POST" autocomplete="off">
             <?php if(isset($_GET['mess']) && $_GET['mess'] == 'error'){ ?>
                <input type="text" 
                     name="title" 
                     style="border-color: #ff6666"
                     placeholder="This field is required" />
              <button type="submit">Add &nbsp; <span>&#43;</span></button>

             <?php }else{ ?>
              <input type="text" 
                     name="title" 
                     placeholder="What do you need to do?" />
              <button type="submit">Add &nbsp; <span>&#43;</span></button>
             <?php } ?>
          </form>
       </div>
       <?php 
          $tdls = $conn->query("SELECT * FROM tdl ORDER BY id DESC");
       ?>
       <div class="show-todo-section">
            <?php if($tdls->rowCount() <= 0){ ?>
                <div class="todo-item">
                    <div class="empty">
                        <img src="img/f.png" width="100%" />
                        <img src="img/Ellipsis.gif" width="80px">
                    </div>
                </div>
            <?php } ?>

            <?php while($tdl = $tdls->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item">
                    <span id="<?php echo $tdl['id']; ?>"
                          class="remove-to-do">x</span>
                    <?php if($tdl['checked']){ ?> 
                        <input type="checkbox"
                               class="check-box"
                               data-tdl-id ="<?php echo $tdl['id']; ?>"
                               checked />
                        <h2 class="checked"><?php echo $tdl['title'] ?></h2>
                    <?php }else { ?>
                        <input type="checkbox"
                               data-tdl-id ="<?php echo $tdl['id']; ?>"
                               class="check-box" />
                        <h2><?php echo $tdl['title'] ?></h2>
                    <?php } ?>
                    <br>
                    <small>created: <?php echo $tdl['date_time'] ?></small> 
                </div>
            <?php } ?>
       </div>
    </div>

    <script src="js/jquery-3.2.1.min.js"></script>

    <script>
        $(document).ready(function(){
            $('.remove-to-do').click(function(){
                const id = $(this).attr('id');
                
                $.post("app/remove.php", 
                      {
                          id: id
                      },
                      (data)  => {
                         if(data){
                             $(this).parent().hide(600);
                         }
                      }
                );
            });

            $(".check-box").click(function(e){
                const id = $(this).attr('data-tdl-id');
                
                $.post('app/check.php', 
                      {
                          id: id
                      },
                      (data) => {
                          if(data != 'error'){
                              const h2 = $(this).next();
                              if(data === '1'){
                                  h2.removeClass('unchecked');
                              }else {
                                  h2.addClass('unchecked');
                              }
                          }
                      }
                );
            });
        });
    </script>
</body>
</html>
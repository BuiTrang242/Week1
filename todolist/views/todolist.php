<?php
ob_start();
$username = isset($_COOKIE['account']) ? $_COOKIE['account'] : '';
$todo_service = new Database('data/todo.json');
$todolist = new Todolist($todo_service);
if (isset($_POST['add'])) {
    $data_todo = $todolist->tasks;
    $title = $_POST['title'];
    $content = $_POST['content'];
    $level = $_POST['level'];
    $id = 1;

    if ($data_todo) {
        $id = count($data_todo) + 1;
    }
    $task = new Task($id, $title, $content, 1, $username, $level);
    $task->save($todo_service);
    header('Location: index.php');

}
$data_todo = $todolist->tasks;
if (isset($_GET['search']) && $_GET['search'] != '') {
    $key = $_GET['search'];
    $data_todo = $todolist->search($todo_service, $key);

}
if (isset($_GET['filter']) && $_GET['filter'] != '') {
    $filter = $_GET['filter'];
    $data_todo = $todolist->filter($todo_service, $filter);
}

$todo_task = [];
$done_task = [];
if ($data_todo) {
    foreach ($data_todo as $task) {
        if ($task['account_created'] != $username) {
            continue;
        }
        if ($task['status'] == 1) {
            $todo_task[] = $task;
        } else {
            $done_task[] = $task;
        }

    }

}
if (isset($_POST['fix'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $level = $_POST['level'];
    $data_update = $todo_service->getAll();

    for ($i = 0; $i < count($data_update); $i++) {
        if ($data_update[$i]['id'] == $id) {
            $task = new Task($data_update[$i]['id'], $title, $content, $data_update[$i]['status'], $data_update[$i]['account_created'], $data_update[$i]['level']);

            $task->update($todo_service);

            break;
        }
    }




    header('Location: index.php');

}


if (isset($_GET['action']) && $_GET['action'] == 'done' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $level = $_GET['level'];
    $data_update = $todo_service->getAll();
    $task = null; // tạo 1 biến $task rỗng
    // tìm task trong file json
    for ($i = 0; $i < count($data_update); $i++) {
        if ($data_update[$i]['id'] == $id) {
            $task = new Task($data_update[$i]['id'], $data_update[$i]['title'], $data_update[$i]['content'], 0, $data_update[$i]['account_created'], $data_update[$i]['level']); // tạo đối tượng cần sửa và chuyển status = 0 => đã xong

            $task->update($todo_service);
            break;
        }
    }
    header('Location: index.php');
}


if (isset($_GET['action']) && $_GET['action'] == 'todo' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $data_update = $todo_service->getAll();
    $task = null; // tạo 1 biến $task rỗng
    // tìm task trong file json
    for ($i = 0; $i < count($data_update); $i++) {
        if ($data_update[$i]['id'] == $id) {
            $task = new Task($data_update[$i]['id'], $data_update[$i]['title'], $data_update[$i]['content'], 1, $data_update[$i]['account_created'], $data_update[$i]['level']); // tạo đối tượng cần sửa và chuyển status = 0 => đã xong

            $task->update($todo_service);
            break;
        }
    }
    header('Location: index.php');
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $todo_service->delete($id);
    header('Location: index.php');
}

?>
<div class="list container"
    style="border: 1px solid #ccc; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin: 20px auto; width: 400px; background-color: #fff; padding: 20px;">
    <h1 style="text-align: center; margin-bottom: 20px;">Form to do list</h1>
    <p style="text-align: center; font-size: 20px; font-weight: bold;">Xin chào <?php echo $username ?> 👏<i
            class="fa-solid fa-smiling-face-with-smiling-eyes"></i></p>
    <div style="display: flex; justify-content: center; align-items: center;">
        <form action="" method="post" style="display: flex; flex-direction: column; align-items: center;width: 100%;">
            <input type="hidden" name="id" id="id" />
            <input type="text" name="title" id="title" placeholder="Add a title"
                style="margin-bottom: 10px; padding: 10px 0; border: 1px solid #ccc; border-radius: 5px; width: 100%;" />
            <input type="text" name="content" id="content" placeholder="Add a content"
                style="margin-bottom: 10px; padding: 10px 0; border: 1px solid #ccc; border-radius: 5px; width: 100%;" />
            <select name="level" id="level"
                style="margin-bottom: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%;">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="hard">Hard</option>

            </select>
            <input type="submit" name="add" id="submit_btn" value="Thêm"
                style="background-color: #4CAF50; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;" />
        </form>
    </div>
    <hr>
    <form action="" method="get" style=";width: 100%; padding:10px 0;display: flex;align-items: center;">

        <div style="display: flex; flex-direction: column; justify-content: center;width: 100%;">
            <label for="search">Search</label>
            <input type="text" value="<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>" name="search"
                id="search" placeholder="Search">

        </div>
        <button type="submit" class="icon">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>

    </form>



    <hr>
    <form action="" method="get" style=";width: 100%; padding:10px 0;display: flex;align-items: center;">

        <div style="display: flex; flex-direction: column; justify-content: center;width: 100%;">
            <label for="filter">Lọc</label>
            <select name="filter" id="filter"
                style="margin-bottom: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%;">
                <option value="low" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'low' ? 'selected' : '' ?>>Low
                </option>
                <option value="medium" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'medium' ? 'selected' : '' ?>>Medium</option>
                <option value="hard" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'hard' ? 'selected' : '' ?>>
                    Hard</option>

            </select>

        </div>
        <button type="submit" class="icon">
            <i class="fa-solid fa-filter"></i>
        </button>

    </form>
    <a href="/"><input type="submit" name="reset" id="submit_btn" value="Reset"
            style="background-color: #4CAF50; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;" /></a>
    <h2 style="text-align: center; margin-top: 20px;">To do</h2>
    <div class="container_form" style="display: flex; flex-direction: column; align-items: center;">
        <?php foreach ($todo_task as $task):
            $color = [
                "low" => "green",
                "medium" => "orange",
                "hard" => "red",
            ]
                ?>
            <div
                style="background-color: <?php echo $color[$task['level']] ?>; width: 100%; height: 50px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; flex-direction: column; justify-content: center;">
                    <div class="title"><?php echo $task['title'] ?></div>
                    <small class="content"><?php echo $task['content'] ?></small>

                </div>
                <div class="icon">
                    <a href="/?action=done&id=<?php echo $task['id'] ?>"><i class="fa-solid fa-check"></i></a>
                    <a data-level="<?php echo $task['level'] ?>" data-id="<?php echo $task['id'] ?>" class="edit_button"
                        style="cursor: pointer;"><i class="fa-solid fa-pen"></i></a>
                    <a href="/?action=delete&id=<?php echo $task['id'] ?>"><i class="fa-solid fa-delete-left"></i></a>
                </div>
            </div>
        <?php endforeach; ?>
        <h2 style="text-align: center; margin-top: 20px;">Completed</h2>
        <?php foreach ($done_task as $task):
            $color = [
                "low" => "green",
                "medium" => "orange",
                "hard" => "red",
            ]
                ?>
            <div
                style="background-color: <?php echo $color[$task['level']] ?>;width: 100%; height: 50px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; flex-direction: column; justify-content: center;">
                    <div style="text-decoration: line-through;"><?php echo $task['title'] ?></div>
                    <small style="text-decoration: line-through;"><?php echo $task['content'] ?></small>
                </div>
                <div class="icon">
                    <a href="/?action=todo&id=<?php echo $task['id'] ?>"><i class="fa-solid fa-rotate-left"></i></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="/?action=logout"
        style="text-decoration: none; color: red; font-weight: bold; text-align: center; display: block; margin-top: 20px;">Đăng
        xuất</a>
</div>
<?php
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    setcookie('account', '', 0, '/');

    header('Location: index.php');
}
?>


<style>
    .list {
        width: 400px;
        height: auto;
        border: 1px solid #ccc;
        text-align: center;
        margin: auto;
    }

    .container_form {

        height: 300px;

        margin: 0 10px;
    }

    .icon i {
        margin: 0 10px 0 0;
    }

    input#update,
    input#add {
        margin: auto;
    }

    input#search {
        padding: 10px 0;
    }
</style>
<script>
    let submit_btn = document.querySelector('#submit_btn');

    const edit_btn = document.querySelectorAll('.edit_button');
    for (let i = 0; i < edit_btn.length; i++) {
        edit_btn[i].addEventListener('click', function (e) {
            e.preventDefault();
            let title = e.target.parentElement.parentElement.parentElement.querySelector('.title');
            let content = e.target.parentElement.parentElement.parentElement.querySelector('.content');
            let id = e.target.parentElement.getAttribute('data-id');
            let level = e.target.parentElement.dataset.level;
            console.log(level);
            console.log(e.target, content, id);
            let title_input = document.querySelector('#title');
            let content_input = document.querySelector('#content');
            let id_input = document.querySelector('#id');
            let level_input = document.querySelector('#level');
            title_input.value = title.innerText;
            content_input.value = content.innerText;
            id_input.value = id;
            level_input.value = level;

            submit_btn.value = 'Cập nhật';
            submit_btn.setAttribute('name', 'fix')

        })
    }


</script>
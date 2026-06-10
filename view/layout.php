<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BookStore - Sách Hay Mỗi Ngày</title>
        <link rel="stylesheet" href="public/css/style.css">
        <link rel="stylesheet" href="public/css/singleproduct.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </head>
    <body>
        <?php
            require_once "model/ModelNews.php";

            $newsModel = new ModelNews();

            $categories = $newsModel->getAllDanhmuc();

            foreach ($categories as &$cat) {
                $cat['theloai'] = $newsModel->getTheloaiByDanhmuc(
                    (int)$cat['IDDanhMuc']
                );
            }
            unset($cat);

            include_once "view/header.php";
            ?>
        <?php include_once "view/header.php"; ?>

        <main>
            <?php require_once "view/route.php"; ?>
        </main>

        <?php include_once "view/footer.php"; ?>
    </body>
</html>

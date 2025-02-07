<?php

    require_once "models/User.php";

    $userModel = new User();

    $fullName = $userModel->getFullName($review->user);

    if($review->user->image == "") {
        $review->user->image = "user.png";
    }

?>
<div class="col-md-12 review">
    <div class="row">
        <div class="col-md-1">
            <div class="profile-image-container review-image" style="background-image: url('<?= $BASE_URL ?>img/users/<?= $review->user->image ?>')"></div>
            <div class="col-md-9 author-details-container">
                <h4 class="author-name">
                    <a href="<?= $BASE_URL ?>profile.php?id=<?= $review->user->id ?>"><?= $fullName ?></a>
                </h4>
                <p><i class="fas fa-star"></i> <?= $movie->rating ?></p>
            </div>
            <div class="col-md-12 author-details-container">
                <p class="comments-title">Comentário:</p>
                <p><?= $review->review ?></p>
            </div>
        </div>
    </div>
</div>
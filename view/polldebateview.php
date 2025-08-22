<?php
// Determine active tab
$activeTab = $_POST['active_tab'] ?? 'poll';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Poll & Debate</title>
    <link rel="stylesheet" href="/animeverse/css/polldebate.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="/animeverse/controller/homecontroller.php">Home</a>
        </div>
        <div class="navbar-right">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="/animeverse/controller/profilecontroller.php">Profile</a>
                <a href="/animeverse/controller/logoutcontroller.php">Logout</a>
            <?php else: ?>
                <a href="/animeverse/controller/logincontroller.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <h1>Poll & Debate Channel</h1>

        <?php if(!empty($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

      
        <div class="tabs">
            <button class="tablink <?= $activeTab=='poll'?'active':'' ?>" onclick="showTab('poll')">Polls</button>
            <button class="tablink <?= $activeTab=='debate'?'active':'' ?>" onclick="showTab('debate')">Debates</button>
        </div>

        <div id="poll" class="tabcontent" style="display: <?= $activeTab=='poll'?'block':'none' ?>;">
            <h2>Create Poll</h2>
            <form method="post">
                <input type="hidden" name="active_tab" value="poll">
                <input type="text" name="poll_title" placeholder="Poll Title" required><br>
                <textarea name="poll_desc" placeholder="Description"></textarea><br>
                <?php for($i=1;$i<=$model->getMaxOptions();$i++): ?>
                    <input type="text" name="poll_options[]" placeholder="Option <?= $i ?>" <?= $i<=2?'required':'' ?>><br>
                <?php endfor; ?>
                <button type="submit">Create Poll</button>
            </form>

            <h2>Polls</h2>
            <?php foreach($polls as $poll): ?>
                <div class="poll">
                    <h3><?= htmlspecialchars($poll['title']) ?></h3>
                    <p><?= htmlspecialchars($poll['description']) ?></p>
                    <form method="post">
                        <input type="hidden" name="active_tab" value="poll">
                        <?php foreach($model->getPollOptions($poll['poll_id']) as $opt): ?>
                            <button type="submit" name="vote_option" value="<?= $opt['option_id'] ?>">
                                <?= htmlspecialchars($opt['option_text']) ?> (<?= $model->getPollVotes($opt['option_id']) ?>)
                            </button>
                        <?php endforeach; ?>
                    </form>
                    <p class="creator">Created by: <?= htmlspecialchars($poll['username']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

       
        <div id="debate" class="tabcontent" style="display: <?= $activeTab=='debate'?'block':'none' ?>;">
            <h2>Create Debate</h2>
            <form method="post">
                <input type="hidden" name="active_tab" value="debate">
                <input type="text" name="debate_title" placeholder="Debate Topic" required><br>
                <textarea name="debate_content" placeholder="Your argument" required></textarea><br>
                <button type="submit">Create Debate</button>
            </form>

            <h2>Debates</h2>
            <?php foreach($debates as $debate): ?>
                <div class="debate">
                    <h3><?= htmlspecialchars($debate['title']) ?></h3>
                    <p><?= htmlspecialchars($debate['content']) ?> - <em><?= htmlspecialchars($debate['username']) ?></em></p>
                    
                    <?php $replies = $model->getDebateReplies($debate['debate_id']); ?>
                    <div class="replies">
                        <?php foreach($replies as $rep): ?>
                            <div class="reply">
                                <p><?= htmlspecialchars($rep['content']) ?> - <strong><?= htmlspecialchars($rep['username']) ?></strong> (Votes: <?= $rep['votes'] ?>)</p>
                                <form method="post" class="inline-form">
                                    <input type="hidden" name="active_tab" value="debate">
                                    <input type="hidden" name="vote_reply_id" value="<?= $rep['reply_id'] ?>">
                                    <button type="submit" name="vote_type" value="up">👍</button>
                                    <button type="submit" name="vote_type" value="down">👎</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <form method="post">
                        <input type="hidden" name="active_tab" value="debate">
                        <input type="hidden" name="debate_id" value="<?= $debate['debate_id'] ?>">
                        <textarea name="debate_reply_content" placeholder="Add your reply" required></textarea>
                        <button type="submit">Reply</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

<script>
function showTab(tabName) {
    const tabs = document.getElementsByClassName('tabcontent');
    for(let t of tabs) t.style.display='none';
    const links = document.getElementsByClassName('tablink');
    for(let l of links) l.classList.remove('active');
    document.getElementById(tabName).style.display='block';
    event.currentTarget.classList.add('active');
}
</script>
</body>
</html>

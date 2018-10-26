

<div id="game-page" class="container-fluid page">
    <div id="game-start-circle-container">
        <div class="circle circle1">
            <a href="#section_1"><h2><span id="game-number-of-players-start-count">1</span><small id="game-start-users-text" class="game-users-plural">user</small><br /><p>Joined</p></h2></a>        </div>
    </div>

    <p id="game-start-warning-text">Please do not refresh this page, wait for the game to start or click the button below to exit the game, else  <strong style="color: green"><?php echo $HomePage->WebsiteDetails->Naira; ?><span id="game-play-amount"></span></strong>  will be deducted when game starts,even without you playing</p>
    <button type="button" id="exit-game-button" class="btn btn-warning btn-circle btn-xl">Exit Game</button>


</div>

<!--
Follow me on
Dribbble: https://dribbble.com/supahfunk
Twitter: https://twitter.com/supahfunk
Codepen: https://codepen.io/supah/
It's just a concept, a fake chat to design a new daily UI for direct messaging.
Hope you like it :)
-->

<div class="chat main-game-chat">
    <div class="chat-title">
        <h1><?php echo $HomePage->WebsiteDetails->SiteName; ?> <span id="game-countdown"></span> <span id="game-potential-winning-container"><span>&#8358;</span> <span id="potential-winning">1000</span></span></h1>
        <h2 id="game-words"></h2>
        <figure class="avatar">
            <img src="<?php echo $HomePage->WebsiteDetails->IMG_FOLDER.'favicon.png'; ?>" /></figure>
    </div>
    <div class="messages">
        <div class="messages-content"></div>
    </div>
    <div id="game-hint-container">
        <span id="game-hints">try another word and i mean it and am also serious</span>
        <span id="game-hint-arrow" class="arrow-down"></span>
    </div>
    <div class="message-box">
        <textarea type="text" id= "game-text" style="" class="message-input" placeholder="Type word..."></textarea>
        <button type="submit" class="message-submit"><i class="fa fa-send"></i> </button>
    </div>

</div>
<div class="bg"></div>
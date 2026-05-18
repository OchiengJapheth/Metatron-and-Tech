<select name="privacy" id="privacy-select" onchange="toggleCustom()">
    <option value="public">Public</option>
    <option value="friends">Friends Only</option>
    <option value="custom">Custom</option>
</select>

<div id="custom-friends" style="display:none;">
    <label>Select friends:</label><br>
    <?php
    $friends = mysqli_query($conn,
        "SELECT users.id, users.username FROM friends 
         JOIN users ON (users.id = friends.sender_id OR users.id = friends.receiver_id)
         WHERE (friends.sender_id='$_SESSION[user_id]' OR friends.receiver_id='$_SESSION[user_id]')
           AND users.id != '$_SESSION[user_id]' AND friends.status='accepted'"
    );
    while($f = mysqli_fetch_assoc($friends)):
    ?>
        <input type="checkbox" name="custom_friends[]" value="<?php echo $f['id']; ?>"> <?php echo $f['username']; ?><br>
    <?php endwhile; ?>
</div>

<script>
function toggleCustom(){
    let select = document.getElementById('privacy-select').value;
    document.getElementById('custom-friends').style.display = (select=='custom') ? 'block' : 'none';
}
</script>

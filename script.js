function viewStory(media, type){
    document.getElementById('storyModal').style.display='flex';
    if(type=='image'){
        document.getElementById('storyImage').src='uploads/'+media;
        document.getElementById('storyImage').style.display='block';
        document.getElementById('storyVideo').style.display='none';
    } else {
        document.getElementById('storyVideo').src='uploads/'+media;
        document.getElementById('storyVideo').style.display='block';
        document.getElementById('storyImage').style.display='none';
    }
}

function closeStory(){
    document.getElementById('storyModal').style.display='none';
    document.getElementById('storyVideo').pause();
}

// fetch notifications every 5 seconds
setInterval(fetchNotifications, 5000);

function fetchNotifications(){
    fetch('fetch_notifications.php')
    .then(response => response.json())
    .then(data => {
        let dropdown = document.getElementById('notif-dropdown');
        let count = document.getElementById('notif-count');
        dropdown.innerHTML = '';
        count.innerText = data.count;
        data.notifications.forEach(n => {
            let div = document.createElement('div');
            div.classList.add('notif-item');
            div.innerText = n.message;
            dropdown.appendChild(div);
        });
    });
}
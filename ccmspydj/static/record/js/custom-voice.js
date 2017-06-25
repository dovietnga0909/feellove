function playAudio(id){
    var audio = document.getElementById(id);
    audio.setAttribute('running-status', 'playing');
    document.getElementById('btn-' + id).innerHTML = '<i class="fa fa-pause"></i>';
    audio.play();
};

function pause(id){
    var audio = document.getElementById(id);
    audio.setAttribute('running-status', 'pause');
    document.getElementById('btn-' + id).innerHTML = '<i class="fa fa-play"></i>';
    audio.pause();
}

function stopAll(){
    var allAudio = document.getElementsByTagName('audio');
    for(var i = 0; i < allAudio.length; i++){
        allAudio[i].pause();
        allAudio[i].currentTime = 0;
        document.getElementById('btn-' +  allAudio[i].id).innerHTML = '<i class="fa fa-play"></i>';
        $('#' + allAudio[i].id).attr('running-status', 'stop');
    }
}

function effect(id){
    if($('#' + id).attr('running-status') === 'stop'){
        stopAll();
        playAudio(id);
        
    }
    else if($('#' + id).attr('running-status') === 'playing'){
        pause(id);
    }
    else if($('#' + id).attr('running-status') === 'pause'){
        playAudio(id);
    }
}

$(window).on('load', function(){
    $('.editor').addClass('invisible');
    onDocumentLoaded();
    function removeActiveMenu(){
        $('ul.nav-main li').each(function(i, e){
            $(e).removeClass('nav-active-custom');
        });
    }

    $('ul.nav-main li').each(function(i, e){			
        $(e).click(function(){	
            removeActiveMenu();			
            $(e).addClass('nav-active-custom');
        })
    })
});
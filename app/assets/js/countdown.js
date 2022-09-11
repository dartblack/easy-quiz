$('document').ready(function () {
    const countdownElement = $('#quiz-countdown');
    setInterval(function () {
        const timer = $('#quiz-countdown').html().split(':');
        let hours = timer[0];
        let minutes = parseInt(timer[1], 10);
        let seconds = parseInt(timer[2], 10);
        --seconds;
        minutes = (seconds < 0) ? --minutes : minutes;
        if (minutes < 0 && seconds < 0) {
            window.location.reload();
        } else {
            seconds = (seconds < 0) ? 59 : seconds;
            minutes = (minutes < 0) ? 59 : minutes;
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            minutes = (minutes < 10) ? '0' + minutes : minutes;
            const newTime = hours + ':' + minutes + ':' + seconds;
            countdownElement.html(newTime);
        }
    }, 1000);
});
$('document').ready(function () {
    const questionSelectElement = $('#Question_mode');
    const answersPanelElement = $('.answers-panel');
    const binaryAnswerPanelElement = $('.binary-correct');
    const questionMode = questionSelectElement.val();

    if (questionMode === 'binary') {
        answersPanelElement.hide();
        binaryAnswerPanelElement.show();
    } else {
        answersPanelElement.show();
        binaryAnswerPanelElement.hide();
    }

    questionSelectElement.change(function () {
        if (questionSelectElement.val() === 'binary') {
            answersPanelElement.hide();
            binaryAnswerPanelElement.show();
        } else {
            answersPanelElement.show();
            binaryAnswerPanelElement.hide();
        }
    });
});
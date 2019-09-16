/*Main Javascript File*/
$(document).ready(function(){
    $('.results-collapse.collapse').on('show.bs.collapse', function(){
        var rowDiv = $(this).parent();
        rowDiv.find(".fa.rotate").addClass("open");
        rowDiv.parent().addClass("selected-row");
    }).on('hide.bs.collapse', function(){
        var rowDiv = $(this).parent();
        rowDiv.find(".fa.rotate").removeClass("open");
        rowDiv.parent().removeClass("selected-row");
    });

    $("#importModal").on("hidden.bs.modal", function() {
        $(this).find('.results-collapse.collapse').collapse("hide");
        $(this).find("input[name='question']").prop("checked", false);
    });
});
function confirmDeleteQuestion() {
    return confirm("Are you sure you want to delete this question? This action cannot be undone.");
}
function confirmDeleteQuestionBlank(questionId) {
    if ($("#questionTextInput"+questionId).val().trim().length < 1) {
        return confirm("Saving this question with blank text will delete this question. Are you sure you want to delete this question? This action cannot be undone.");
    } else {
        return true;
    }
}
function showNewQuestionRow() {
    var addQuestionsSection = $("#addQuestions");
    var questionRow = $("#newQuestionRow");

    addQuestionsSection.hide();
    questionRow.fadeIn();
    var theForm = $("#questionTextForm-1");
    theForm.find('#questionTextInput-1').focus()
        .off("keypress").on("keypress", function(e) {
            if(e.which === 13) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: theForm.prop("action"),
                    data: theForm.serialize(),
                    success: function(data) {
                        $("#questionTextInput-1").val('');
                        var nextNumber = questionRow.data("question-number") + 1;
                        $("#newQuestionNumber").text(nextNumber + '.');
                        questionRow.data("question-number", nextNumber);
                        $("#newQuestionRow").before(data.new_question);
                        $("#flashmessages").html(data.flashmessage);
                        setupAlertHide();
                        questionRow.hide();
                        addQuestionsSection.show();
                    }
                });
            }
        });
    $("#questionSaveAction-1").off("click").on("click", function(e) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: theForm.prop("action"),
            data: theForm.serialize(),
            success: function(data) {
                $("#newQuestionRow").before(data.new_question);
                $("#questionTextInput-1").val('');
                var nextNumber = $(".question-number").last().parent().data("question-number") + 1;
                $("#newQuestionNumber").text(nextNumber + '.');
                questionRow.data("question-number", nextNumber);
                $("#flashmessages").html(data.flashmessage);
                setupAlertHide();
                questionRow.hide();
                addQuestionsSection.show();
            }
        });
    });
    $("#questionCancelAction-1").off("click").on("click", function(e) {
        $("#questionTextInput-1").val('');
        questionRow.hide();
        addQuestionsSection.show();
    });
}
function editQuestionText(questionId) {
    var questionText =$("#questionText"+questionId);
    questionText.hide();
    $("#questionDeleteAction"+questionId).hide();
    $("#questionEditAction"+questionId).hide();
    $("#questionReorderAction"+questionId).hide();

    var theForm = $("#questionTextForm"+questionId);

    theForm.show();
    theForm.find('#questionTextInput'+questionId).focus()
        .off("keypress").on("keypress", function(e) {
            if(e.which === 13) {
                e.preventDefault();
                if ($('#questionTextInput'+questionId).val().trim() === '') {
                    if(confirmDeleteQuestionBlank(questionId)) {
                        // User entered blank question text and wants to delete.
                        deleteQuestion(questionId, true);
                    }
                } else {
                    // Still has text in question. Save it.
                    $.ajax({
                        type: "POST",
                        url: theForm.prop("action"),
                        data: theForm.serialize(),
                        success: function(data) {
                            questionText.text($('#questionTextInput'+questionId).val());
                            questionText.show();
                            $("#questionDeleteAction"+questionId).show();
                            $("#questionEditAction"+questionId).show();
                            $("#questionReorderAction"+questionId).show();
                            $("#questionSaveAction"+questionId).hide();
                            $("#questionCancelAction"+questionId).hide();
                            theForm.hide();
                            $("#flashmessages").html(data.flashmessage);
                            setupAlertHide();
                        }
                    });
                }
            }
        });
    $("#questionSaveAction"+questionId).show()
        .off("click").on("click", function(e) {
            if ($('#questionTextInput'+questionId).val().trim() === '') {
                if(confirmDeleteQuestionBlank(questionId)) {
                    // User entered blank question text and wants to delete.
                    deleteQuestion(questionId, true);
                }
            } else {
                // Still has text in question. Save it.
                $.ajax({
                    type: "POST",
                    url: theForm.prop("action"),
                    data: theForm.serialize(),
                    success: function(data) {
                        questionText.text($('#questionTextInput'+questionId).val());
                        questionText.show();
                        $("#questionDeleteAction"+questionId).show();
                        $("#questionEditAction"+questionId).show();
                        $("#questionReorderAction"+questionId).show();
                        $("#questionSaveAction"+questionId).hide();
                        $("#questionCancelAction"+questionId).hide();
                        theForm.hide();
                        $("#flashmessages").html(data.flashmessage);
                        setupAlertHide();
                    }
                });
            }
    });

    $("#questionCancelAction"+questionId).show()
        .off("click").on("click", function(e) {
        var theText = $("#questionText"+questionId);
        theText.show();
        theForm.hide();
        $("#questionTextInput"+questionId).val(theText.text());
        $("#questionDeleteAction"+questionId).show();
        $("#questionEditAction"+questionId).show();
        $("#questionReorderAction"+questionId).show();
        $("#questionSaveAction"+questionId).hide();
        $("#questionCancelAction"+questionId).hide();
    });
}
function editTitleText() {
    $("#toolTitle").hide();
    var titleForm = $("#toolTitleForm");
    titleForm.show();
    titleForm.find("#toolTitleInput").focus()
        .off("keypress").on("keypress", function(e) {
        if(e.which === 13) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                dataType: "json",
                url: titleForm.prop("action"),
                data: titleForm.serialize(),
                success: function(data) {
                    $(".title-text-span").text($("#toolTitleInput").val());
                    var titleText = $("#toolTitle");
                    titleText.show();
                    titleForm.hide();
                    $("#toolTitleCancelLink").hide();
                    $("#toolTitleSaveLink").hide();
                    $("#flashmessages").html(data.flashmessage);
                    setupAlertHide();
                }
            });
        }
    });
    $("#toolTitleSaveLink").show()
        .off("click").on("click", function(e) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: titleForm.prop("action"),
                data: titleForm.serialize(),
                success: function(data) {
                    $(".title-text-span").text($("#toolTitleInput").val());
                    var titleText = $("#toolTitle");
                    titleText.show();
                    titleForm.hide();
                    $("#toolTitleCancelLink").hide();
                    $("#toolTitleSaveLink").hide();
                    $("#flashmessages").html(data.flashmessage);
                    setupAlertHide();
                }
            });
        });
    $("#toolTitleCancelLink").show()
        .off("click").on("click", function(e) {
            var titleText = $("#toolTitle");
            titleText.show();
            titleForm.hide();
            $("#toolTitleInput").val($(".title-text-span").text());
            $("#toolTitleCancelLink").hide();
            $("#toolTitleSaveLink").hide();
        });
}
function moveQuestionUp(questionId) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "actions/ReorderQuestion.php?PHPSESSID=" + $("#sess").val(),
        data: {
            "question_id": questionId
        },
        success: function(data) {
            var theQuestionMoved = $("#questionRow" + questionId);
            theQuestionMoved.hide();
            var currentNumber = theQuestionMoved.data("question-number");
            console.log('current num: ' + currentNumber);
            if (currentNumber === 1) {
                // Move to bottom
                $("#newQuestionRow").before(theQuestionMoved);
            } else {
                // Move up one
                theQuestionMoved.prev().before(theQuestionMoved);
            }
            // Fix up question numbers
            var questionNum = 1;
            $(".question-number").each(function() {
                $(this).text(questionNum + ".");
                $(this).parent().data("question-number", questionNum);
                questionNum++;
            });

            theQuestionMoved.fadeIn("fast");

            $("#flashmessages").html(data.flashmessage);
            setupAlertHide();
        }
    });
}
function deleteQuestion(questionId, skipconfirm = false) {
    if (skipconfirm || confirmDeleteQuestion()) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "actions/DeleteQuestion.php?PHPSESSID=" + $("#sess").val(),
            data: {
                "question_id": questionId
            },
            success: function(data) {
                $("#questionRow" + questionId).remove();
                // Fix up question numbers
                var questionNum = 1;
                $(".question-number").each(function() {
                    $(this).text(questionNum + ".");
                    $(this).parent().data("question-number", questionNum);
                    questionNum++;
                });
                // Fix new question number
                $("#newQuestionRow").data("question-number", questionNum);
                $("#newQuestionNumber").text(questionNum + ".");

                $("#flashmessages").html(data.flashmessage);
                setupAlertHide();
            }
        });
    }
}
function answerQuestion(questionId) {
    var answerForm = $("#answerForm" + questionId);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: answerForm.prop("action"),
        data: answerForm.serialize(),
        success: function(data) {
            if (data.answer_content) {
                answerForm.replaceWith(data.answer_content);
            }
            console.log(data.flashmessage);
            $("#flashmessages").html(data.flashmessage);
            console.log($("#flashmessages").html());
            setupAlertHide();
        }
    });
}
function setupAlertHide() {
    // On load hide any alerts after 3 seconds
    setTimeout(function() {
        $(".alert-banner").slideUp();
    }, 3000);
}

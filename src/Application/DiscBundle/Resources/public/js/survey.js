var survey = {};
var activeButtonClass = 'btn-success';

$(document).ready(function () {

    $(fields).each(function (i, e) {
        survey[e.id] = {
            most: 0,
            least: 0
        };
    });

    $('.survey-form').on('click', 'button.send', function (e) {

        var data = {
            user: user,
            survey: survey
        };
        
        var json = JSON.stringify(data);

        e.preventDefault();
        var form = $(this).closest('form');
        form.find('input[name=json]').val(json);

        Spinner.show();
        form.submit();
    });

    $('#survey').on('click', 'span.rate a', function (e) {

        e.preventDefault();
        var self = $(this);
        self.blur();

        var temp = self.attr('id').split('-');
        var type = temp.shift();
        var opositeType = type === 'most' ? 'least' : 'most';
        var expression = parseInt(temp.shift());
        var field = self.closest('.panel').attr('id').replace(/\D/g, '');

        if (survey[field][opositeType] == expression) {
            survey[field][opositeType] = 0;
        }

        survey[field][type] = survey[field][type] == expression ? 0 : parseInt(expression);

        // Uaktualnienie GUI na podstawie danych:

        var currentCount = 0;

        for (var f in survey) {
            if (survey.hasOwnProperty(f)) {
                var fieldContainer = $('#field-' + f);
                var fieldContainerBody = fieldContainer.find('.panel-body');

                // Reset pola:
                fieldContainer
                        .removeClass('panel-default')
                        .removeClass('panel-success')
                        ;
                fieldContainerBody
                        .removeClass('bg-success')
                        ;

                // Reset guzików:
                fieldContainer.find('.btn').removeClass('most').removeClass('least');

                var fieldExpressions = survey[f];

                if ((fieldExpressions.most > 0) && (fieldExpressions.least > 0)) {
                    currentCount++;
                    fieldContainer.addClass('panel-success');
                    fieldContainerBody.addClass('bg-success');
                } else {
                    fieldContainer.addClass('panel-default');
                }

                for (var fkey in fieldExpressions) {
                    if (fieldExpressions.hasOwnProperty(fkey)) {
                        var val = fieldExpressions[fkey];
                        var selector = '#' + fkey + '-' + val;
                        $(selector).addClass(fkey);
                    }
                }
            }
        }

        // Reset guzika wysyłki
        var sendButton = $('.send');
        sendButton.removeClass(activeButtonClass).addClass('btn-default').addClass('disabled');

        // Uaktywnienie guzika wysyłki, jeżeli odpowiedziano już na wszystkie pytania:
        if (currentCount === Object.keys(survey).length) {
            sendButton.addClass(activeButtonClass).removeClass('btn-default').removeClass('disabled');
        }
    });
});

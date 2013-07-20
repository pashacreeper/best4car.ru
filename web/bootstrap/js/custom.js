var mainLayout = function(){
    // Top menu hover effect
    $('.navTopItem').mouseenter(function() {                                  
        $(this).children().css("opacity", "1");
    });
    $('.navTopItem').mouseleave(function() {
        $(this).children().css("opacity", "0.5");
    });
    // Login form
    $('.enterDropdown').hide();
    $('.enter').click(function() {
        $('.enterDropdown').toggle();
    }); 
    $('.btnEnter').click(function() {
        $('.enterDropdown').hide();
    });
    // User menu
    $('.userDropdown').hide();
    $('.btnUser').click(function() {
        $('.userDropdown').toggle();
    });

    var loadRegistrationForm = function($registrationContainer, registrationUrl, data){
        $registrationContainer.empty();
        $registrationContainer.append(data);
        $form = $registrationContainer.find('form');
        $form.submit(function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: registrationUrl,
                data: $form.serialize()
            }).done(function(data){
                if ('redirect' == data) {
                    window.location.replace(Routing.generate('_index'));
                } else {
                    loadRegistrationForm($registrationContainer, registrationUrl, data);
                }
            });
        });
    };
    
    // Registration popup
    var showRegistrationPopup = function(linkElement){
        var $this = $(linkElement),
            $registrationFormPopup = $('#registration-form-popup'),
            $registrationContainer = $('#registration-container'),
            registrationUrl = Routing.generate('fos_user_registration_register');

        $this.parent().trigger('reveal:close');
        $registrationFormPopup.reveal($(this).data());
        $.get(registrationUrl, function(data){
            loadRegistrationForm($registrationContainer, registrationUrl, data);
        });

    };

    $('#carOwnerRegister').on('click', function(){
        showRegistrationPopup(this);
    });
    $('#carOwnerRegisterFromTour').on('click', function(){
        showRegistrationPopup(this);
    });

    $('#resettingPassword').on('click', function(){
        var $this = $(this),
            $resettingContainer = $('#resetting-container');

        $resettingContainer.empty();

        $.get(Routing.generate('fos_user_resetting_request'), function(data){
            $resettingContainer.append(data);
        });
    });

    // Подстановка значений в строку поиска
    var $wrapper = $('#searchWrapper'),
        $inputSearch = $('#inputSearch');
    $wrapper.on('click', '.exampleName', function(){
        $this = $(this);
        $inputSearch.val($this.html());
    });
    $wrapper.on('click', 'button', function(e){
        e.preventDefault();
        if (!$inputSearch.val()) {
            $inputSearch.val($inputSearch.attr("placeholder"));
        }
        $wrapper.find('form').submit();
    });

    // Работа со списком возможных вариантов подстановки
    (function(){
        var $variantList = $('#variantList'),
            $variantContainer = $('#variantContainer'),
            variants = [],
            currentIndex = 0,
            variantsLength = 0;

        $variantList.find('li').each(function(index, element){
            variants[index] = $(element).html();
        });

        variantsLength = variants.length;

        $variantContainer.html(variants[currentIndex]);

        $wrapper.on('click', '.btnLeft' , function(){
            if (currentIndex <= 0) {
                currentIndex = variantsLength - 1;
            } else {
                currentIndex--;
            }
            $variantContainer.html(variants[currentIndex]);
        });

        $wrapper.on('click', '.btnRight' , function(){
            if (currentIndex == variantsLength - 1) {
                currentIndex = 0;
            } else {
                currentIndex++;
            }
            $variantContainer.html(variants[currentIndex]);
        });
    })();
};

var catalogPage = function(){
    // Отображаем и скрываем меню «В первый раз на сайте»
    if (CookieHandler.get('popup_for_new_closed')) {
        $('.popupFirstVisit').hide();
    };
    $('.popupClose').click(function() {
        $('.popupFirstVisit').hide();
        CookieHandler.set('popup_for_new_closed', true, (3*24*60*60), '/');
    });

    $('#advancedSearch').hide();
    $('#toggleAdvancedSearch').on("click", function(){
        var $this = $(this),
            defaultText = 'Показать расширенный поиск',
            closeText = 'Скрыть расширенный поиск',
            advancedSearch = $('#advancedSearch');

        $this.parent().toggleClass('showRightSearch');
        advancedSearch.toggle();
        if (advancedSearch.is(':hidden')) {
            $this.html(defaultText);
            $('#map').css('right', '0');
        } else {
            $this.html(closeText);
            $('#map').css('right', '340px');
        }
    });

    var slider1 = $('.bxslider1').bxSlider({
        mode: 'fade',
        captions: true
    });

    var slider2 = $('.bxslider2').bxSlider({
        mode: 'fade',
        captions: true
    });
    $('.linkFirstVisitPeople').on('click', function(){
        slider1.reloadSlider();
    });
    $('.linkFirstVisitCompany').on('click', function(){
        slider2.reloadSlider();
    });

    // Отрабатываем нажатие по «Только с акциями» в меню расширенного поиска
    // start
    jQuery(document).ready(function(){
        jQuery(".checkBox__autograf__wrap").mousedown(function() {
            changeCheck(jQuery(this));
        });
        jQuery(".checkBox__autograf__wrap").each(function() {
            changeCheckStart(jQuery(this));
        });
    });
    var changeCheck = function(el) {
        var el = el,
            input = el.find("input").eq(0);
        if(!input.attr("checked")) {
            el.css("background-position","0 -24px");    
            input.attr("checked", true)
        } else {
            el.css("background-position","0 0");    
            input.attr("checked", false)
        }
        return true;
    }
    var changeCheckStart = function (el) {
        var el = el,
        input = el.find("input").eq(0);
        if(input.attr("checked")) {
            el.css("background-position","0 -24px");    
        }
        return true;
    }

    // Advanced search filter
    $(document).ready(function(){
        // slider
        var slider = $("#slider-range-max").slider({
            range: "max",
            min: 1,
            max: 10,
            value: 4,
            slide: function( event, ui ) {
                $("#amount").val( ui.value );
            }
        });
        $("#amount").val( $("#slider-range-max").slider("value") );

        $('.btnFiltr').click(function(){
            $(this).toggleClass('btnFiltrActive');
        });

        // Clear filter
        $('#cleanSearch').click(function(){
            $('.btnFiltr').each(function(index, element){
                $element = $(element);
                $prevElement = $element.prev();

                if ($element.hasClass('btnFiltrActive')) {
                    $element.removeClass('btnFiltrActive');
                }
                if ($prevElement.is(':checked')) {
                    $prevElement.attr('checked', false);
                }
            });
            $('#amount').val(4);
            slider.slider("value", 4);

            $('#advancedSearch select').each(function(index, element){
                $element = $(element);
                $element.find(':selected').removeAttr("selected");
                $element.find("option:first").attr("selected", "selected");
                $element.trigger("liszt:updated");
            });
        });
    });
};

var dealsPage = function(){
    $('.actionItemLink').mouseenter(function() {                                  
        $(this).children('.actionItemBottomWrap').css("top", "90px");
    }); 
    $('.actionItemLink').mouseleave(function() {
        $(this).children('.actionItemBottomWrap').css("top", "120px");
    });
    // Deals menu
    $('.menuLeftBar').on('click', 'li', function(){
        var $this = $(this),
            $menu = $('.menuLeftBar'),
            point = $('<i class="subActive"></i>'),
            dealsType = $this.data('deal-type');

        if (! $this.hasClass('activeBar')) {
            $activeItem = $menu.find('.activeBar');
            $activeItem.find('i').remove();
            $activeItem.removeClass('activeBar');

            $this.addClass('activeBar');
            $this.append(point);
            loadDealsFromMenu($, Routing, dealsType);
        }
    });
};

var registrationPage = function(){
    $('#submitRegisterForm').on('click', function(){
        $('#registerForm').submit();
    });
    $('#datetimepicker1').datetimepicker({
        pickTime: false
    });

    var appendIcons = function(link){
        var checkboxes = $(link).parent().find('.wrapper').find('input[type="checkbox"]:checked'),
            container = $('#' + $(link).data('container'));

        checkboxes.each(function(index, element){
            var element = $(element),
                labelText = element.next('label').html()
                checkedViewElement = '<li class="tagTicketServices"><span class="sto"></span>' + labelText + '</li>'

            container.append(checkedViewElement);
        });

        $(link).parent().trigger('reveal:close');   
    }

    $('#specializationSave').on('click', function(e){
        e.preventDefault();
        appendIcons(this);
    });
    $('#serviceSave').on('click', function(e){
        e.preventDefault();
        appendIcons(this);
    });

    (function(){
        var tabLinksContainer = $('#stepRegistration');

        $('.btnNext').on('click', function(){
            var tabId = $(this).data('tab-switch-id');
            tabLinksContainer.find('.active').removeClass('active');
            tabLinksContainer.find('#'+tabId).addClass('active');
        });
    })();

    $('#sto_content_company_registration_logo').on('change', function(){
        var reader = new FileReader(),
            thisElem = $(this);

        reader.onload = function (e) {
            $('#picture-preview-wrapper').append('<img style="max-width: 200px; margin-top: 10px;" src="'+e.target.result+'">');
            $('#picture-preview-wrapper').after('<a class="deleteImg clear" id="deleteImg">Удалить<i class="icon-remove-circle"></i></a>');
        };

        $('#general-data').on('click', '#deleteImg', function(){
            $('#picture-preview-wrapper').find('img').remove();
            $(this).remove();
        });
        reader.readAsDataURL($(this)[0].files[0]);
    });
};

var feedbackPage = function(){
    $('#datetimepicker1').datetimepicker({
        pickTime: false
    });
    $('#datetimepicker2').datetimepicker({
        pickTime: false
    });
}

var profilePage = function(){
    (function(){
        var tabContainers = $('.tabs'),
            tabLinksContainer = $('.tabs ul.tabNavigation'),
            url = document.URL,
            hash = false;

        tabContainers.find('> div').hide();
        if (url.indexOf('#') + 1) {
            hash = url.substring(url.indexOf('#'));
        }

        if (hash) {
            tabContainers.find('[data-tab-id="'+hash+'"]').show();
            tabLinksContainer.find('a[href="' + hash + '"]').addClass('selected');
        } else {
            tabContainers.find('> div').hide().filter(':first').show();
            tabLinksContainer.find('a:first').addClass('selected');
        }
        tabLinksContainer.find('a').click(function () {
            tabContainers.find('> div').hide(); // прячем все табы
            tabContainers.find('[data-tab-id="'+this.hash+'"]').show(); // показываем содержимое текущего
            $('.tabs ul.tabNavigation a').removeClass('selected'); // у всех убираем класс 'selected'
            $(this).addClass('selected'); // текушей вкладке добавляем класс 'selected'
        });
    })();
};

var companyPage = function(){
    $('.accordion-body').hide();
    $('.accordion-toggle').click(function(){
        $('#collapse_' + this.id).toggle();
        $('#iconCollapse_'+ this.id).toggleClass('iconChevronDown');
    });
    $('.showAll').click(function(){
        $('.accordion-body').show();
        $('.iconAccord').removeClass('iconChevronDown');
    });  
};

var initPage = function(){
    mainLayout();
    catalogPage();
    dealsPage();
    registrationPage();
    feedbackPage();
    profilePage();
    companyPage();
};

$(document).ready(function(){initPage()});
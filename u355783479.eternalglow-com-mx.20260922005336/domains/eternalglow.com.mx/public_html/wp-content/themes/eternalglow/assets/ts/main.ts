import * as bootstrap from 'bootstrap';
import { Fancybox } from '@fancyapps/ui';


let isReady = false;
let isLoaded = false;
const JSS_APP = window.JSS_APP;

const onResize = () => {

};

const onScroll = () => {
    const scroll_top = $(window).scrollTop();
    const $go_top = $('#go-top');
    if (scroll_top >= 200) {
        $go_top.addClass('active');
    } else {
        $go_top.removeClass('active');
    }
};
 
const scrollTo = (targetOffset: any, speed: any) => {
    $('html, body').animate({ scrollTop: targetOffset }, speed);
};

const getHash = (url: string) => {
    let hash = '';
    if(url.indexOf('#') >= 0){
        hash = url.substring(url.indexOf('#'));
    }
    return hash;
};

const scrollTarget = (target: any, id: any = false) => {
    const href = $(target).attr('href');
    const dataScroll = $(target).data('scroll');
    const dataHref = $(target).data('href');
    let targetOffset = 0;
    if(!id){
        if (dataScroll === undefined) {
            if (dataHref === undefined) {
                targetOffset = $(href).offset().top;
            } else {
                targetOffset = $(dataHref).offset().top;
            }
        } else {
            targetOffset = dataScroll;
        }
    } else {
        targetOffset = $(id).offset().top;
    }
    targetOffset -= $('header').outerHeight();
    targetOffset = targetOffset < 0 ? 0: targetOffset;
    scrollTo(targetOffset, 900);
};

const addToast = (type = 'info', title = '', message = '') => {
    const bg = type == 'error' ? 'danger': type;
    const div = $('<div>', {
        class: `toast text-bg-${bg}`,
        'data-bs-delay': 5000,
    });
    div.html(`
        <div class="toast-header text-bg-${bg}">
            <span class='mr-auto'>${title}</span>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `);
    $('#toasts').prepend(div);

    const toast = new bootstrap.Toast(div[0]);
    toast.show();

    div[0].addEventListener('hidden.bs.toast', (e) => {
        $(e.currentTarget).remove();
    });
}

const showModal = (id: string) => {
    const modalElementList = document.querySelectorAll('.modal.show')
    const modalList = [].slice.call(modalElementList).map((modalEl: HTMLElement) => {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.hide();
    });

    const modal = bootstrap.Modal.getOrCreateInstance(id);
    modal.show();
}

window.showModal = showModal;

$(async () => {

    isReady = true;

    $('.scroll-to, .scroll-to-a a').on('click', e => {
        scrollTarget(e.currentTarget);
        e.preventDefault();
    });

    $('.btn-service').on('click', e => {
        const $this = $(e.currentTarget);
        const service = $this.attr('href')
        $('.btn-service, .services-content').removeClass('active');
        $this.addClass('active');
        $(service).addClass('active');
    });

    const windowsHash = getHash(window.location.href);
    if(windowsHash && $(windowsHash).length && windowsHash != '#'){
        const $service = $(windowsHash);
        if($service.hasClass('services')){
            $('.btn-service, .services-content').removeClass('active');
            const tab = $service.data('tab');
            console.log(tab)
            $(tab).addClass('active');
            $service.addClass('active');
        }
    }

    Fancybox.bind("[data-fancybox]", {
    // Your custom options
    });

    onResize();
    onScroll();
	console.log('ready');
});

$(window).on('resize', () => {

    onResize();
    onScroll();
});

$(window).on('scroll', () => {

    onResize();
    onScroll();
});

$(window).on('load', () => {
    isLoaded = true;
    
	if(isReady){

    }

    const windowsHash = getHash(window.location.href);
    if(windowsHash && $(windowsHash).length && windowsHash != '#'){
        scrollTarget('', windowsHash);
    }

    onResize();
    onScroll();
    console.log('load');
});
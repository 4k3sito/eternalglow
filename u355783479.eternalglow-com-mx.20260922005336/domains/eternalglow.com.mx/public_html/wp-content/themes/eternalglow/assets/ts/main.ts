import * as bootstrap from 'bootstrap';
import { Fancybox } from '@fancyapps/ui';


let isReady = false;
let isLoaded = false;
const JSS_APP = window.JSS_APP;

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

// Editorial pages: rows fade up as they enter the viewport. The hidden state is added here,
// so without JS (or before it runs) everything is visible. The first row is the hero and paints at once.
const revealRows = () => {
    if (!document.body.classList.contains('lp-editorial') || !('IntersectionObserver' in window)) {
        return;
    }
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-in');
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -10% 0px' });
    document.querySelectorAll('.wpb-content-wrapper > .vc_row:not(:first-child), main > section:not(:first-child)').forEach(row => {
        row.classList.add('lp-reveal');
        observer.observe(row);
    });
};

// Tagline reveal: each word of a .lp-words heading lights up as its line crosses the middle of the screen.
// Words are staggered with a transition delay instead of one observer per word.
const revealWords = () => {
    if (!('IntersectionObserver' in window)) {
        return;
    }
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.querySelectorAll('span').forEach(word => word.classList.add('is-in'));
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -40% 0px' });
    document.querySelectorAll<HTMLElement>('.lp-words').forEach(heading => {
        const words = (heading.textContent || '').trim().split(' ');
        heading.textContent = '';
        words.forEach((word, i) => {
            const span = document.createElement('span');
            span.textContent = word;
            span.style.transitionDelay = `${i * 120}ms`;
            heading.append(span, ' ');
        });
        observer.observe(heading);
    });
};

// Before and after tabs: show only the photos of the chosen treatment.
const filterResults = () => {
    const tabs = document.querySelectorAll<HTMLButtonElement>('.lp-tabs button');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const filter = tab.dataset.filter;
            tabs.forEach(t => t.setAttribute('aria-pressed', String(t === tab)));
            document.querySelectorAll<HTMLElement>('.lp-results > li').forEach(item => {
                item.hidden = filter !== 'all' && item.dataset.category !== filter;
            });
            document.querySelector('.lp-results')?.scrollTo({ left: 0 });
        });
    });
};

// Catalogue (/tratamientos/): category pills and the search box filter the same rows.
// Accents are ignored, so "menton" finds "mentón".
const filterCatalog = () => {
    const search = document.querySelector<HTMLInputElement>('#lp-search');
    const pills = document.querySelectorAll<HTMLButtonElement>('.lp-pills button');
    if (!search) {
        return;
    }
    const plain = (text: string) => text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    let category = 'all';
    const apply = () => {
        const query = plain(search.value.trim());
        let found = 0;
        document.querySelectorAll<HTMLElement>('.lp-group').forEach(group => {
            let rows = 0;
            group.querySelectorAll<HTMLElement>('li').forEach(row => {
                row.hidden = query !== '' && plain(row.textContent || '').indexOf(query) < 0;
                rows += row.hidden ? 0 : 1;
            });
            group.hidden = rows === 0 || (category !== 'all' && group.dataset.category !== category);
            found += group.hidden ? 0 : rows;
        });
        document.getElementById('lp-empty').hidden = found > 0;
    };
    const choose = (next: string) => {
        category = next;
        pills.forEach(pill => pill.setAttribute('aria-pressed', String(pill.dataset.filter === category)));
        apply();
    };
    pills.forEach(pill => pill.addEventListener('click', () => choose(pill.dataset.filter)));
    search.addEventListener('input', apply);
    // Links from the home arrive as /tratamientos/#botox: open that category.
    const hash = window.location.hash.slice(1);
    if (document.querySelector(`.lp-pills button[data-filter="${hash}"]`)) {
        choose(hash);
    }
};

// Treatment links open a booking sheet instead of the treatment's page: the visitor came to book.
// The page stays one link away inside the sheet. Modified clicks (new tab) keep working as links.
const bookingSheet = () => {
    const sheet = document.querySelector<HTMLDialogElement>('#lp-sheet');
    if (!sheet || typeof sheet.showModal !== 'function') {
        return;
    }
    const fill = (key: string, text: string) => {
        sheet.querySelector<HTMLElement>(`[data-sheet-${key}]`).textContent = text;
    };
    const whatsapp = sheet.querySelector<HTMLAnchorElement>('.lp-btn-wa');
    const more = sheet.querySelector<HTMLAnchorElement>('[data-sheet-more]');
    document.addEventListener('click', e => {
        const link = (e.target as Element).closest<HTMLAnchorElement>('a[data-sheet]');
        if (!link || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey) {
            return;
        }
        e.preventDefault();
        const data = link.dataset;
        fill('cat', data.cat);
        fill('name', data.name);
        fill('desc', data.desc);
        fill('price', data.price);
        sheet.querySelector<HTMLElement>('[data-sheet-pricebox]').hidden = !data.price;
        if (whatsapp) {
            const url = new URL(whatsapp.href);
            url.searchParams.set('text', sheet.dataset.waText.replace('%s', data.name));
            whatsapp.href = url.toString();
        }
        // Rows without a page of their own point to booking or to this same page: nothing more to read.
        more.href = link.href;
        more.hidden = link.origin !== window.location.origin || link.pathname === window.location.pathname;
        sheet.showModal();
    });
    // A click on the backdrop lands on the dialog element itself.
    sheet.addEventListener('click', e => {
        if (e.target === sheet) {
            sheet.close();
        }
    });
};

// Analytics: which button on the page was used (hero, header, final, flotante, servicio...).
// The click events themselves (click_agendar_cita, click_whatsapp) are sent by the tracking snippet in wp-admin.
const trackCta = () => {
    document.addEventListener('click', e => {
        const link = (e.target as Element).closest<HTMLAnchorElement>('a[data-cta]');
        if (link && typeof window.gtag === 'function') {
            window.gtag('event', 'cta_click', { cta_location: link.dataset.cta, link_url: link.href });
        }
    });
};

// Phones: the fixed booking bar steps aside while another booking button or the contact form is on screen.
const stickyBar = () => {
    const bar = document.getElementById('lp-bar');
    if (!bar || !('IntersectionObserver' in window)) {
        return;
    }
    const onScreen = new Set<Element>();
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => entry.isIntersecting ? onScreen.add(entry.target) : onScreen.delete(entry.target));
        bar.classList.toggle('is-hidden', onScreen.size > 0);
    });
    document.querySelectorAll('.lp-actions, #contacto form').forEach(el => observer.observe(el));
};

// The burger turns into a cross while the mobile menu is open.
const menuState = () => {
    const menu = document.getElementById('offcanvasNavbar');
    menu?.addEventListener('show.bs.offcanvas', () => document.body.classList.add('lp-menu-open'));
    menu?.addEventListener('hide.bs.offcanvas', () => document.body.classList.remove('lp-menu-open'));
    // Anchor links inside the menu have to close it, or the page scrolls behind the overlay.
    menu?.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', () => bootstrap.Offcanvas.getInstance(menu)?.hide());
    });
};

$(async () => {

    isReady = true;

    revealRows();
    revealWords();
    filterResults();
    filterCatalog();
    bookingSheet();
    menuState();
    trackCta();
    stickyBar();

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
            $(tab).addClass('active');
            $service.addClass('active');
        }
    }

    Fancybox.bind("[data-fancybox]", {
    // Your custom options
    });

});

$(window).on('load', () => {
    isLoaded = true;
    
	if(isReady){

    }

    const windowsHash = getHash(window.location.href);
    if(windowsHash && $(windowsHash).length && windowsHash != '#'){
        scrollTarget('', windowsHash);
    }

});
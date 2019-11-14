import 'nodelist-foreach-polyfill';
import documentReady from 'document-ready';

class Importer {
    constructor() {
        this.terminate = false;
        this.loadWrapper = document.querySelector('#tl_load_action');
        this.prepareWrapper = document.querySelector('#tl_prepare_action');
        this.importWrapper = document.querySelector('#tl_import_action');
        this.errortWrapper = document.querySelector('#error');
        this.loadProgressbar = this.loadWrapper.querySelector('.progress-bar-inner');
        this.prepareProgressbar = this.prepareWrapper.querySelector('.progress-bar-inner');
        this.importProgressbar = this.importWrapper.querySelector('.progress-bar-inner');
        this.debug = ((location.href.search('app_dev.php') > 1) ? '/app_dev.php' : '');
    }

    init() {
        let self = this;
        document.querySelector('#terminate').addEventListener('click', function (event) {
            event.preventDefault();

            self.terminate = true;
        });

        this.load();
    }

    load() {
        if (this.terminate) {
            return false;
        }

        let route = this.debug +'/contao/cb/member/import/load';
        let self = this;
        this.loadWrapper.classList.remove('not_active');

        this.request(route, function (response) {
            if (response.progress < 100) {
                self.loadProgressbar.style.width = response.progress + '%';
                self.load();

                return false;
            }

            self.loadProgressbar.addEventListener('transitionend', function () {
                self.prepare();
            });

            self.loadProgressbar.style.width = response.progress + '%';
        });
    }

    prepare() {
        if (this.terminate) {
            return false;
        }

        let route = this.debug + '/contao/cb/member/import/prepare';
        let self = this;
        this.prepareWrapper.classList.remove('not_active');

        this.request(route, function (response) {
            self.prepareProgressbar.style.width = response.progress + '%';
            if (response.progress < 100) {
                self.prepare();

                return false;
            }

            self.prepareProgressbar.style.width = response.progress + '%';
            self.prepareProgressbar.addEventListener('transitionend', function () {
                self.import();
            });
        });
    }

    import() {
        if (this.terminate) {
            return false;
        }

        let route = this.debug + '/contao/cb/member/import/import';
        let self = this;
        this.importWrapper.classList.remove('not_active');

        this.request(route, function (response) {
            self.importProgressbar.style.width = response.progress + '%';
            if (response.progress < 100) {
                self.import();

                return false;
            }

            self.importProgressbar.style.width = response.progress + '%';
            self.importProgressbar.addEventListener('transitionend', function () {
                self.finish();
            });
        });
    }

    finish() {
        location.href = this.debug + '/contao?do=member';
    }

    request(route, callback) {
        if (this.terminate) {
            return false;
        }

        let request = new XMLHttpRequest();
        let self = this;

        request.onreadystatechange = function () {
            if (4 === request.readyState) {
                let response = JSON.parse(request.responseText);
                if (undefined === response.error) {
                    callback(response);

                    return true;
                }

                self.errortWrapper.innerHTML = response.error;
            }
        };

        request.open('GET', route);
        request.send();
    }
}

documentReady(function () {
    const importer = new Importer();
    importer.init();
});

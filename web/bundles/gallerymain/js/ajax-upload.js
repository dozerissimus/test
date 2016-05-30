window.AjaxUpload = function (button, options) {
        this._settings = {
            action: 'upload.php',
            name: 'userfile',
            data: {},
            autoSubmit: true,
            responseType: false,
            hoverClass: 'hover',
            focusClass: 'focus',
            disabledClass: 'disabled',
            onChange: function (file, extension) {},
            onSubmit: function (file, extension) {},
            onComplete: function (file, response) {}
        };
        for (var i in options) {
            if (options.hasOwnProperty(i)) {
                this._settings[i] = options[i];
            }
        }
        if (button.jquery) {
            button = button[0];
        } else if (typeof button == "string") {
            if (/^#.*/.test(button)) {
                button = button.slice(1);
            }
            button = document.getElementById(button);
        }
        if (!button || button.nodeType !== 1) {
            throw new Error("Please make sure that you're passing a valid element");
        }
        if (button.nodeName.toUpperCase() == 'A') {
            addEvent(button, 'click', function (e) {
                if (e && e.preventDefault) {
                    e.preventDefault();
                } else if (window.event) {
                    window.event.returnValue = false;
                }
            });
        }
        this._button = button;
        this._input = null;
        this._disabled = false;
        this.enable();
        this._rerouteClicks();
    };
    AjaxUpload.prototype = {
        setData: function (data) {
            this._settings.data = data;
        },
        disable: function () {
            addClass(this._button, this._settings.disabledClass);
            this._disabled = true;
            var nodeName = this._button.nodeName.toUpperCase();
            if (nodeName == 'INPUT' || nodeName == 'BUTTON') {
                this._button.setAttribute('disabled', 'disabled');
            }
            if (this._input) {
                this._input.parentNode.style.visibility = 'hidden';
            }
        },
        enable: function () {
            removeClass(this._button, this._settings.disabledClass);
            this._button.removeAttribute('disabled');
            this._disabled = false;
        },
        _createInput: function () {
            var self = this;
            var input = document.createElement("input");
            input.setAttribute('type', 'file');
            input.setAttribute('name', this._settings.name);
            addStyles(input, {
                'position': 'absolute',
                'right': 0,
                'margin': 0,
                'padding': 0,
                'fontSize': '480px',
                'fontFamily': 'sans-serif',
                'cursor': 'pointer'
            });
            var div = document.createElement("div");
            addStyles(div, {
                'display': 'block',
                'position': 'absolute',
                'overflow': 'hidden',
                'margin': 0,
                'padding': 0,
                'opacity': 0,
                'direction': 'ltr',
                'zIndex': 5000
            });
            if (div.style.opacity !== "0") {
                if (typeof(div.filters) == 'undefined') {
                    throw new Error('Opacity not supported by the browser');
                }
                div.style.filter = "alpha(opacity=0)";
            }
            addEvent(input, 'change', function () {
                if (!input || input.value === '') {
                    return;
                }
                var file = fileFromPath(input.value);
                if (false === self._settings.onChange.call(self, file, getExt(file))) {
                    self._clearInput();
                    return;
                }
                if (self._settings.autoSubmit) {
                    self.submit();
                }
            });
            addEvent(input, 'mouseover', function () {
                addClass(self._button, self._settings.hoverClass);
            });
            addEvent(input, 'mouseout', function () {
                removeClass(self._button, self._settings.hoverClass);
                removeClass(self._button, self._settings.focusClass);
                input.parentNode.style.visibility = 'hidden';
            });
            addEvent(input, 'focus', function () {
                addClass(self._button, self._settings.focusClass);
            });
            addEvent(input, 'blur', function () {
                removeClass(self._button, self._settings.focusClass);
            });
            div.appendChild(input);
            document.body.appendChild(div);
            this._input = input;
        },
        _clearInput: function () {
            if (!this._input) {
                return;
            }
            removeNode(this._input.parentNode);
            this._input = null;
            this._createInput();
            removeClass(this._button, this._settings.hoverClass);
            removeClass(this._button, this._settings.focusClass);
        },
        _rerouteClicks: function () {
            var self = this;
            addEvent(self._button, 'mouseover', function () {
                if (self._disabled) {
                    return;
                }
                if (!self._input) {
                    self._createInput();
                }
                var div = self._input.parentNode;
                copyLayout(self._button, div);
                div.style.visibility = 'visible';
            });
        },
        _createIframe: function () {
            var id = getUID();
            var iframe = toElement('<iframe src="javascript:false;" name="' + id + '" />');
            iframe.setAttribute('id', id);
            iframe.style.display = 'none';
            document.body.appendChild(iframe);
            return iframe;
        },
        _createForm: function (iframe) {
            var settings = this._settings;
            var form = toElement('<form method="post" enctype="multipart/form-data"></form>');
            form.setAttribute('action', settings.action);
            form.setAttribute('target', iframe.name);
            form.style.display = 'none';
            document.body.appendChild(form);
            for (var prop in settings.data) {
                if (settings.data.hasOwnProperty(prop)) {
                    var el = document.createElement("input");
                    el.setAttribute('type', 'hidden');
                    el.setAttribute('name', prop);
                    el.setAttribute('value', settings.data[prop]);
                    form.appendChild(el);
                }
            }
            return form;
        },
        _getResponse: function (iframe, file) {
            var toDeleteFlag = false,
                self = this,
                settings = this._settings;
            addEvent(iframe, 'load', function () {
                if (iframe.src == "javascript:'%3Chtml%3E%3C/html%3E';" || iframe.src == "javascript:'<html></html>';") {
                    if (toDeleteFlag) {
                        setTimeout(function () {
                            removeNode(iframe);
                        }, 0);
                    }
                    return;
                }
                var doc = iframe.contentDocument ? iframe.contentDocument : window.frames[iframe.id].document;
                if (doc.readyState && doc.readyState != 'complete') {
                    return;
                }
                if (doc.body && doc.body.innerHTML == "false") {
                    return;
                }
                var response;
                if (doc.XMLDocument) {
                    response = doc.XMLDocument;
                } else if (doc.body) {
                    response = doc.body.innerHTML;
                    if (settings.responseType && settings.responseType.toLowerCase() == 'json') {
                        if (doc.body.firstChild && doc.body.firstChild.nodeName.toUpperCase() == 'PRE') {
                            doc.normalize();
                            response = doc.body.firstChild.firstChild.nodeValue;
                        }
                        if (response) {
                            response = eval("(" + response + ")");
                        } else {
                            response = {};
                        }
                    }
                } else {
                    response = doc;
                }
                settings.onComplete.call(self, file, response);
                toDeleteFlag = true;
                iframe.src = "javascript:'<html></html>';";
            });
        },
        submit: function () {
            var self = this,
                settings = this._settings;
            if (!this._input || this._input.value === '') {
                return;
            }
            var file = fileFromPath(this._input.value);
            if (false === settings.onSubmit.call(this, file, getExt(file))) {
                this._clearInput();
                return;
            }
            var iframe = this._createIframe();
            var form = this._createForm(iframe);
            removeNode(this._input.parentNode);
            removeClass(self._button, self._settings.hoverClass);
            removeClass(self._button, self._settings.focusClass);
            form.appendChild(this._input);
            form.submit();
            removeNode(form);
            form = null;
            removeNode(this._input);
            this._input = null;
            this._getResponse(iframe, file);
            this._createInput();
        }
    };

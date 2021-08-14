
let manufacturer = ({dataUrl}) => {

    let manufacturers = []
    let pageXOffset = 0;
    let pageYOffset = 0;
    let manufacturerId = '';

    let icon = '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-right-square-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">\n' +
        '  <path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm2.5 8.5a.5.5 0 0 1 0-1h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5z"/>\n' +
        '</svg>';

    let DomHelper = {
        removeElements: (selector) => {
            document.querySelectorAll(selector)
                .forEach(e => e.parentNode.removeChild(e));
        },
        hideElements: (selector) => {
            document.querySelectorAll(selector)
                .forEach(el => el.classList.add('d-none'));
        },
        hideElement: (selector) => {
            let el = document.querySelector(selector);
            if (el) {
                el.classList.add('d-none');
            }
        },
        showElements: (selector) => {
            document.querySelectorAll(selector)
                .forEach(el => el.classList.remove('d-none'));
        },
        showElement: (selector) => {
            let el = document.querySelector(selector);
            if (el) {
                el.classList.remove('d-none');
            }
        }
    }

    let FilterModel = {
        query: '',
        guitars: false,
        pickups: false,
        strings: false,
        amps: false,
        gear: false,
        list: [],
        filter: () => {
            FilterModel.list = manufacturers.filter((item) => {
                return item.name.toLowerCase().includes(FilterModel.query)
                    && (!FilterModel.guitars || (item.guitars === 1))
                    && (!FilterModel.pickups || (item.pickups === 1))
                    && (!FilterModel.strings || (item.strings === 1))
                    && (!FilterModel.amps || (item.amps === 1))
                    && (!FilterModel.gear || (item.gear === 1));
            })
        }
    };

    let FilterForm = {
        view: () => {
            return m('form.form-inline',
                m('div.form-group',
                    m('input.form-control[type=text]', {
                        value: FilterModel.query,
                        placeholder: 'Suchen...',
                        oninput: e => {
                            FilterModel.query = e.target.value.toLowerCase();
                            FilterModel.filter();
                            DomHelper.hideElements('tr[data-target-id]');
                            DomHelper.showElements('tr[data-id]');
                        }
                    })
                ),
                m('div.checkbox',
                    m('label',
                        m('input[type=checkbox]', {
                            onchange: e => {
                                FilterModel.guitars = e.target.checked;
                                FilterModel.filter();
                                DomHelper.hideElements('tr[data-target-id]');
                                DomHelper.showElements('tr[data-id]');
                            }
                        }),
                        'Instrumente'
                    )
                ),
                m('div.checkbox',
                    m('label',
                        m('input[type=checkbox]', {
                            onchange: e => {
                                FilterModel.amps = e.target.checked;
                                FilterModel.filter();
                                DomHelper.hideElements('tr[data-target-id]');
                                DomHelper.showElements('tr[data-id]');
                            }
                        }),
                        'Verstärker'
                    )
                ),
                m('div.checkbox',
                    m('label',
                        m('input[type=checkbox]', {
                            onchange: e => {
                                FilterModel.strings = e.target.checked;
                                FilterModel.filter();
                                DomHelper.hideElements('tr[data-target-id]');
                                DomHelper.showElements('tr[data-id]');
                            }
                        }),
                        'Saiten'
                    )
                ),
                m('div.checkbox',
                    m('label',
                        m('input[type=checkbox]', {
                            onchange: e => {
                                FilterModel.pickups = e.target.checked;
                                FilterModel.filter();
                                DomHelper.hideElements('tr[data-target-id]');
                                DomHelper.showElements('tr[data-id]');
                            }
                        }),
                        'Pickups'
                    )
                ),
                m('div.checkbox',
                    m('label',
                        m('input[type=checkbox]', {
                            onchange: e => {
                                FilterModel.gear = e.target.checked;
                                FilterModel.filter();
                                DomHelper.hideElements('tr[data-target-id]');
                                DomHelper.showElements('tr[data-id]');
                            }
                        }),
                        'Zubehör'
                    )
                ),
            )
        }
    };

    let ErrorPage = {
        view: () => {
            return m('div.row',
                m('div.col-md-12',
                    m('h1', 'Fehler'),
                    m('p', 'Test')
                )
            );
        }
    };

    let DetailComponent = {
        view: ({attrs: {model: model}}) => {
            return [
                m('h1', model.name),
                m('p', model.text),
                m('h3', 'Sortiment'),
                model.assortment ? m('ul', model.assortment.map(item => m('li', item))) : '',
                model.visitUrl ? m('div',
                    m('h3', 'Website'),
                    m('a[rel=nofollow][target=_blank]', {
                        href: model.visitUrl,
                    }, model.website)
                ) : '',
                model.wikipedia ? m('div', m('h3', 'Wikipedia'),
                    m('a[rel=nofollow][target=_blank]', {
                        href: model.wikipedia,
                    }, model.wikipedia)
                ) : ''
            ]
        }
    }

    let Detail = {
        oninit: (vnode) => {
            vnode.state.model = manufacturers.find((item) => {
                return item.id == m.route.param('id');
            });
            pageXOffset = window.pageXOffset;
            pageYOffset = window.pageYOffset;
            window.scrollTo(0, 0);
        },
        view: ({state: {model: model}}) => {
            return m('div.row',
                m('div.col-12.col-md-6',
                    m(DetailComponent, {model: model}),
                    m('br'),
                    m('p.icon', m(m.route.Link, {
                            href: '/',
                        }, 'Alle Hersteller anzeigen')
                    )
                )
            );
        }
    };

    let List = {
        oninit: FilterModel.filter,
        view: () => {
            //window.scrollTo(pageXOffset, pageYOffset);
            return [
                m('h1.title', 'E-Bass Hersteller und Marken'), m(FilterForm), FilterModel.list.length > 0
                    ? m('table.table',
                        m('thead',
                            m('tr',
                                m('th[width=95%]', 'Name'),
                                m('th', '')
                            )
                        ),
                        m('tbody#mf1',
                            FilterModel.list.map((manufacturer) => [
                                m('tr', {
                                    'data-id': manufacturer.id,
                                    onclick: (e) => {
                                        let id = e.target.closest('tr').dataset.id;
                                        DomHelper.showElement('tr[data-target-id="' + id + '"]');
                                        DomHelper.hideElement('tr[data-id="' + id + '"]');
                                    }
                                }, [
                                    m('td', manufacturer.name),
                                    m('td.icon.text-right', m(m.route.Link, {
                                            href: '/' + manufacturer.id,
                                        }, m.trust(icon))
                                    )
                                ]),
                                m('tr.d-none', {
                                    'data-target-id': manufacturer.id,
                                    onclick: (e) => {
                                        let id = e.target.closest('tr').dataset.targetId;
                                        DomHelper.showElement('tr[data-id="' + id + '"]');
                                        DomHelper.hideElement('tr[data-target-id="' + id + '"]');
                                    }
                                }, m('td[colspan=2]',
                                    m('div.row',
                                        m('div.col-12.col-md-6', {style:"margin:2em 0;"},
                                            m(DetailComponent, {model: manufacturer}),
                                        )
                                    )))
                                ]
                            )
                        )
                    )
                    : m('tr',
                        m('td[colspan=2', 'Keine Hersteller gefunden.')
                    )
            ];
        }
    }

    m.request({method: "GET", url: dataUrl})
        .then((data) => {
            manufacturers = JSON.parse(data);
            m.route(document.querySelector('#ctrl-manufacturer-index'), '/', {
                '/:id': Detail,
                '/': List,
                '/:404...': ErrorPage
            });
        });

}

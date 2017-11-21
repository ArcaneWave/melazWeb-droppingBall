function getCookie(name) {
    const matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function getBoolCookie(name) {
    return getCookie(name).toLowerCase() === 'true';
}

const app = new Vue({
    el: '#app',
    data: {
        using_complex_gravity: getBoolCookie('using_complex_gravity'),
        using_archimedes_force: getBoolCookie('using_archimedes_force'),
        using_environment_resistance: getBoolCookie('using_environment_resistance'),
        static_vars: {},
        coordinate_time_data: {
            all: [],
            options: {
                title: 'График зависимости высоты от времени',
                hAxis: {title: 'Время (с)'},
                vAxis: {title: 'Высота (м)'},
                explorer: {
                    actions: ['dragToZoom', 'rightClickToReset'],
                    axis: 'horizontal',
                    keepInBounds: true,
                    maxZoomIn: 10.0,
                },
                crosshair: {
                    trigger: 'both'
                }
            },
            chart: null,
            table: null
        },
    },
    methods: {
        save_table: function () {
            let link = document.getElementById('save-table');
            const table = document.getElementById('experiment-table').outerHTML;
            const file = new Blob([table], {type: 'application/excel'});
            link.href = URL.createObjectURL(file);
            link.download = "table.xls";
        },
        animate_ball: function (id) {
            let ball = document.getElementById(id);
            const h = (this.coordinate_time_data.all[2][0] - this.coordinate_time_data.all[1][0]) * 1000;
            const max = this.coordinate_time_data.all.slice(1).reduce(function (accumulator, currentValue) {
                return Math.max(accumulator, currentValue[1]);
            }, 0);

            step = function step(that) {
                if (i < that.coordinate_time_data.all.length) {
                    const xi = 50 - that.coordinate_time_data.all[i][1] * 50 / max;
                    const xi_1 = 50 - that.coordinate_time_data.all[i - 1][1] * 50 / max;

                    animate(function (dt) {
                        const x = xi_1 + (xi - xi_1) / h * dt;
                        ball.style.marginTop = 'calc(' + x + 'vh - 50px)';
                    }, h);

                    if (xi === 50) {
                        return;
                    }

                    i++;
                    setTimeout(step, h, that);
                }
            };

            let i = 2;
            step(this);
        },
        static_using_complex_gravity: function () {
            if (!this.static_vars.hasOwnProperty('using_complex_gravity')) {
                this.static_vars.using_complex_gravity = this.using_complex_gravity;
            }

            return this.static_vars.using_complex_gravity;
        },
        static_using_archimedes_force: function () {
            if (!this.static_vars.hasOwnProperty('using_archimedes_force')) {
                this.static_vars.using_archimedes_force = this.using_archimedes_force;
            }

            return this.static_vars.using_archimedes_force;
        },
        static_using_environment_resistance: function () {
            if (!this.static_vars.hasOwnProperty('using_environment_resistance')) {
                this.static_vars.using_environment_resistance = this.using_environment_resistance;
            }

            return this.static_vars.using_environment_resistance;
        }
    }
});

function animate(draw_callback, duration) {
    const start = performance.now();

    requestAnimationFrame(function animate(time) {
        let timePassed = time - start;

        if (timePassed > duration) {
            timePassed = duration;
        }

        draw_callback(timePassed);

        if (timePassed < duration) {
            requestAnimationFrame(animate);
        }
    });
}
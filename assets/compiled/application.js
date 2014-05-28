// Generated by CoffeeScript 1.7.1
(function() {
  var App;

  App = Ember.Application.create({
    LOG_TRANSITIONS: true
  });

  App.GraphCanvasComponent = Ember.Component.extend({
    tagName: 'canvas',
    didInsertElement: function() {
      var canvas, chart, context, data, jqcanv, options;
      canvas = this.get('element');
      jqcanv = $(canvas);
      context = canvas.getContext('2d');
      canvas.width = jqcanv.parent().width();
      canvas.height = 200;
      options = {};
      if (this.get('options') !== void 0) {
        options = this.get('options');
      }
      data = {
        labels: this.generateLables(),
        datasets: this.get('data')
      };
      if (this.get('type') === 'PolarArea') {
        data = this.get('data');
      }
      return chart = new Chart(context)[this.get('type')](data, options);
    },
    generateLables: function() {
      var h, i, lables, m, _i, _j, _k, _l, _ref;
      lables = [];
      if (this.get('range') === 'month') {
        for (m = _i = 1; _i <= 31; m = ++_i) {
          lables.push(this.stp(m));
        }
      } else if (this.get('range') === 'day') {
        for (h = _j = 0; _j < 24; h = ++_j) {
          for (m = _k = 0; _k < 60; m = _k += 15) {
            lables.push(this.stp(h) + ':' + this.stp(m));
          }
        }
        for (i = _l = 0, _ref = lables.length; _l <= _ref; i = _l += 2) {
          lables[i - 1] = "";
        }
      } else if (this.get('range') === 'direction') {
        lables = ['N', 'NO', 'O', 'SO', 'S', 'SW', 'W', 'NW'];
      }
      return lables;
    },
    stp: function(num) {
      if (parseInt(num) < 10) {
        num = "0" + num;
      }
      return num;
    }
  });

  App.ApplicationController = Ember.Controller.extend({
    init: function() {
      $.getJSON("api/get/notification/", (function(_this) {
        return function(payload) {
          return _this.set('newWarnings', payload.data.length);
        };
      })(this));
      return this._super();
    },
    newWarnings: 0,
    hasWarnings: (function() {
      return this.get('newWarnings') > 0;
    }).property('newWarnings'),
    textWarnings: (function() {
      if (this.get('newWarnings') === 1) {
        return 'Warnung';
      } else {
        return 'Warnungen';
      }
    }).property('newWarnings')
  });

  App.NotificationsController = Ember.Controller.extend({
    actions: {
      markRead: function(id) {
        return console.log(id);
      }
    }
  });

  App.WeatherController = Ember.Controller.extend({
    monthList: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
    initializeData: (function() {
      var data, dataday, dataset, day, daylist, entry, hm, num, rv, tm, wd, ws, _i, _j, _len, _len1, _ref;
      dataset = this.get('content');
      dataday = {};
      daylist = {};
      _ref = dataset.data;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        data = _ref[_i];
        if (dataday[data.day] === void 0) {
          dataday[data.day] = new Array;
        }
        dataday[data.day].push(data);
      }
      for (num in dataday) {
        day = dataday[num];
        hm = 0;
        rv = 0;
        tm = 0;
        wd = 0;
        ws = 0;
        for (_j = 0, _len1 = day.length; _j < _len1; _j++) {
          entry = day[_j];
          hm += entry.humidity;
          rv += entry.rain_volume;
          tm += entry.temperature;
          wd += entry.wind_direction;
          ws += entry.wind_speed;
        }
        daylist[num] = {
          humidity: hm / day.length,
          rain_volume: rv / day.length,
          temperature: tm / day.length,
          wind_direction: wd / day.length,
          wind_speed: ws / day.length
        };
      }
      this.set('datasetAll', daylist);
      return this.generateDatasets();
    }).observes('content'),
    generateDatasets: function() {
      var avgHm, avgRv, avgTm, avgWs, dataHm, dataRv, dataTm, dataWd, dataWs, day, days, graphdataDir, graphdataOne, graphdataTwo, num, _ref;
      avgTm = 0;
      avgRv = 0;
      avgWs = 0;
      avgHm = 0;
      dataTm = [];
      dataRv = [];
      dataWs = [];
      dataHm = [];
      dataWd = [0, 0, 0, 0, 0, 0, 0, 0];
      _ref = this.get('datasetAll');
      for (num in _ref) {
        day = _ref[num];
        dataTm.push(day.temperature);
        dataRv.push(day.rain_volume);
        dataWs.push(day.wind_speed);
        dataHm.push(day.humidity);
        dataWd[Math.floor(day.wind_direction / 45)] += 1;
        avgTm += day.temperature;
        avgRv += day.rain_volume;
        avgWs += day.wind_speed;
        avgHm += day.humidity;
      }
      days = dataTm.length;
      this.set('avgTm', (avgTm / days).toFixed(2));
      this.set('avgRv', (avgRv / days).toFixed(2));
      this.set('avgWs', (avgWs / days).toFixed(2));
      this.set('avgHm', (avgHm / days).toFixed(2));
      graphdataOne = [
        {
          strokeColor: "rgba(28,134,238,1)",
          pointColor: "rgba(28,134,238,1)",
          data: dataTm
        }, {
          strokeColor: "rgba(71,60,139,1)",
          pointColor: "rgba(71,60,139,1)",
          data: dataRv
        }
      ];
      graphdataTwo = [
        {
          strokeColor: "rgba(28,134,238,1)",
          pointColor: "rgba(28,134,238,1)",
          data: dataWs
        }, {
          strokeColor: "rgba(71,60,139,1)",
          pointColor: "rgba(71,60,139,1)",
          data: dataHm
        }
      ];
      graphdataDir = [
        {
          fillColor: "rgba(220,220,220,0.5)",
          strokeColor: "rgba(220,220,220,1)",
          pointColor: "rgba(220,220,220,1)",
          pointStrokeColor: "#fff",
          data: dataWd
        }
      ];
      this.set('datasetOne', graphdataOne);
      this.set('datasetTwo', graphdataTwo);
      return this.set('datasetDir', graphdataDir);
    },
    currentMonth: (function() {
      var monthList;
      monthList = this.get('monthList');
      return monthList[parseInt(this.get('month')) - 1] + " " + this.get('year');
    }).property('year', 'month'),
    prevMonth: (function() {
      var monthList, thisMonth;
      monthList = this.get('monthList');
      thisMonth = parseInt(this.get('month'));
      if (thisMonth === 1) {
        thisMonth = 13;
      }
      return monthList[thisMonth - 2];
    }).property('month'),
    nextMonth: (function() {
      var monthList, thisMonth;
      monthList = this.get('monthList');
      thisMonth = parseInt(this.get('month'));
      if (thisMonth === 12) {
        thisMonth = 0;
      }
      return monthList[thisMonth];
    }).property('month'),
    strPad: function(num) {
      if (parseInt(num) < 10) {
        return "0" + num;
      } else {
        return num;
      }
    },
    actions: {
      goPrev: function() {
        var month, year;
        month = parseInt(this.get('month')) - 1;
        year = parseInt(this.get('year'));
        if (month === 0) {
          month = 12;
          year -= 1;
        }
        this.setProperties({
          'year': year,
          'month': month
        });
        return this.transitionToRoute('weather', year, this.strPad(month));
      },
      goNext: function() {
        var month, year;
        month = parseInt(this.get('month')) + 1;
        year = parseInt(this.get('year'));
        if (month === 13) {
          month = 1;
          year += 1;
        }
        this.setProperties({
          'year': year,
          'month': month
        });
        return this.transitionToRoute('weather', year, this.strPad(month));
      }
    }
  });

  App.Router.map(function() {
    this.route('notifications');
    return this.route('weather', {
      path: '/weather/:year/:month'
    });
  });

  App.IndexRoute = Ember.Route.extend({
    beforeModel: function() {
      return this.transitionTo('weather', this.getCurrentYear(), this.getCurrentMonth());
    },
    getCurrentYear: function() {
      var currentDate;
      currentDate = new Date();
      return currentDate.getFullYear();
    },
    getCurrentMonth: function() {
      var currentDate, currentMonth;
      currentDate = new Date();
      currentMonth = currentDate.getMonth() + 1;
      if (currentMonth < 10) {
        currentMonth = "0" + currentMonth;
      }
      return currentMonth;
    }
  });

  App.NotificationsRoute = Ember.Route.extend({
    model: function() {
      return $.getJSON("api/get/notification/", (function(_this) {
        return function(payload) {
          return payload;
        };
      })(this));
    }
  });

  App.WeatherRoute = Ember.Route.extend({
    model: function(params) {
      this.set('year', params.year);
      this.set('month', params.month);
      return $.getJSON("api/get/weatherdata/" + params.year + "-" + params.month, (function(_this) {
        return function(payload) {
          if (payload.success) {
            return payload;
          } else {
            return _this.transitionToRoute('notfound');
          }
        };
      })(this));
    },
    setupController: function(controller, model) {
      this._super(controller, model);
      return this.controllerFor('weather').setProperties({
        'year': this.get('year'),
        'month': this.get('month')
      });
    }
  });

  App.WeatherView = Ember.View.extend({
    lineOptions: {
      datasetFill: false,
      scaleLineWidth: 2,
      pointDotRadius: 1,
      pointDotStrokeWidth: 2
    },
    areaOptions: {}
  });

}).call(this);

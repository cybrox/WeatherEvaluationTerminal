App.WeatherController = Ember.Controller.extend
  
  monthList: [
    'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli',
    'August', 'September', 'Oktober', 'November', 'Dezember'
  ]


  # Value for month overview graphs -------------------------------------------
  initializeMonthData: (->
    dataset = @get('content')
    dataday = {}
    daylist = {}

    # Check if there even is stuff
    @setProperties(
      'noData': (dataset.data.length == 0),
      'numMes': dataset.data.length)
    if dataset.data.length == 0 then return;

    for data in dataset.data
      dataday[data.day] = new Array if dataday[data.day] == undefined
      dataday[data.day].push(data)

    for num, day of dataday
      hm = 0; rv = 0; tm = 0; wd = 0; ws = 0; 
      for entry in day
        hm += entry.humidity
        rv += entry.rain_volume
        tm += entry.temperature
        wd += entry.wind_direction
        ws += entry.wind_speed

      daylist[num] =
        day: num
        humidity: hm/day.length
        rain_volume: rv/day.length
        temperature: tm/day.length
        wind_direction: wd/day.length
        wind_speed: ws/day.length

    @setProperties(
      'availableDays': Object.keys(daylist)
      'selectedDay': Object.keys(daylist)[0]
      'datasetAll': daylist
    )
    @generateDatasets('month', @get('datasetAll'))
  ).observes('content')



  # Value for day overview graphs ---------------------------------------------
  initializeDayData: (->
    thisDay = @get('selectedDay')
    dayNumr = 0
    dayData = {}

    for element in @get('content').data
      if parseInt(element.day) == parseInt(thisDay)
        dayData[dayNumr] = element
        dayNumr++;

    @generateDatasets('day', dayData)
  ).observes('content', 'selectedDay')



  # Generator for chartjs compatible data -------------------------------------
  generateDatasets: (scope, dataIs)->
    avgTm = 0; avgRv = 0; avgWs = 0; avgHm = 0; dd = 0;
    dataTm = []; dataRv = []; dataWs = [];dataHm = [];
    dataWd = [0,0,0,0,0,0,0,0];

    # Fill empty days if scope is set to month
    if scope == 'month'
      for num in [1..28]
        if dataIs[num] == undefined and num < dataIs[Object.keys(dataIs)[Object.keys(dataIs).length - 1]]['day']
          dataIs[num] = {humidity: 0, rain_volume: 0, temperature: 0, wind_direction: 0, wind_speed: 0}

    for num,day of dataIs
      dataTm.push(day.temperature)
      dataRv.push(day.rain_volume)
      dataWs.push(day.wind_speed)
      dataHm.push(day.humidity)
      dataWd[(Math.floor(day.wind_direction/45))] += 1;

      avgTm += day.temperature
      avgRv += day.rain_volume
      avgWs += day.wind_speed
      avgHm += day.humidity

    graphdataOne = [{
        strokeColor : "rgba(28,134,238,1)",
        pointColor : "rgba(28,134,238,1)",
        data : dataTm
      },{
        strokeColor : "rgba(71,60,139,1)",
        pointColor : "rgba(71,60,139,1)",
        data : dataRv
    }]

    graphdataTwo = [{
        strokeColor : "rgba(28,134,238,1)",
        pointColor : "rgba(28,134,238,1)",
        data : dataWs
      },{
        strokeColor : "rgba(71,60,139,1)",
        pointColor : "rgba(71,60,139,1)",
        data : dataHm
    }]

    graphdataDir = [{
      fillColor : "rgba(220,220,220,0.5)",
      strokeColor : "rgba(220,220,220,1)",
      pointColor : "rgba(220,220,220,1)",
      pointStrokeColor : "#fff",
      data : dataWd
    }]

    data = {}
    days = dataTm.length
    data[scope+'Graph'] = 
      'one': graphdataOne
      'two': graphdataTwo
      'dir': graphdataDir

    data[scope+'Avg'] = 
      'tm': (avgTm / days).toFixed(2)
      'rv': (avgRv / days).toFixed(2)
      'ws': (avgWs / days).toFixed(2)
      'hm': (avgHm / days).toFixed(2)


    @setProperties(data)



  # Month-picker methods ------------------------------------------------------
  currentMonth: (->
    monthList = @get('monthList')
    monthList[(parseInt(@get('month'))-1)]+" "+@get('year')
  ).property('year', 'month')

  prevMonth: (->
    monthList = @get('monthList')
    thisMonth = parseInt(@get('month'))
    thisMonth = 13 if thisMonth == 1
    monthList[(thisMonth-2)]
  ).property('month')

  nextMonth: (->
    monthList = @get('monthList')
    thisMonth = parseInt(@get('month'))
    thisMonth = 0 if thisMonth == 12
    monthList[thisMonth]
  ).property('month')

  strPad: (num) ->
    if parseInt(num) < 10 then "0"+num else num

  actions:
    goPrev: ->
      month = parseInt(@get('month')) - 1
      year  = parseInt(@get('year'))

      if(month == 0)
        month = 12
        year -= 1

      @setProperties('year': year, 'month': month, 'loading': true)
      @transitionToRoute('weather', year, @strPad(month))

    goNext: ->
      month = parseInt(@get('month')) + 1
      year  = parseInt(@get('year'))

      if(month == 13)
        month = 1
        year += 1

      @setProperties('year': year, 'month': month, 'loading': true)
      @transitionToRoute('weather', year, @strPad(month))
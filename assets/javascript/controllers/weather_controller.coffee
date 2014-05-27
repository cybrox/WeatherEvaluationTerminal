App.WeatherController = Ember.Controller.extend
  
  monthList: [
    'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli',
    'August', 'September', 'Oktober', 'November', 'Dezember'
  ]



  dataset1: [
    {
      fillColor : "rgba(220,220,220,0.5)",
      strokeColor : "rgba(220,220,220,1)",
      pointColor : "rgba(220,220,220,1)",
      pointStrokeColor : "#fff",
      data : [65,59,90,81,56,55,40]
    },
    {
      fillColor : "rgba(151,187,205,0.5)",
      strokeColor : "rgba(151,187,205,1)",
      pointColor : "rgba(151,187,205,1)",
      pointStrokeColor : "#fff",
      data : [28,48,40,19,96,27,100]
    }
  ]

  dataset2: [
    {
      fillColor : "rgba(220,220,220,0.5)",
      strokeColor : "rgba(220,220,220,1)",
      pointColor : "rgba(220,220,220,1)",
      pointStrokeColor : "#fff",
      data : [65,59,90,81,56,55,40,10]
    }
  ]

  # Value for month overview graphs -------------------------------------------
  initializeData: (->
    dataset = @get('content')
    dataday = {}
    daylist = {}
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
        humidity: hm/day.length
        rain_volume: rv/day.length
        temperature: tm/day.length
        wind_direction: wd/day.length
        wind_speed: ws/day.length

    @set('datasetAll', daylist)
    @generateDatasets()
  ).observes('content')

  generateDatasets: ->
    dataOne = []; dataTwo = [];
    for num,day of @get('datasetAll')
      dataOne.push(day.temperature)
      dataTwo.push(day.rain_volume)

    graphdata = [
      {
        fillColor : "rgba(220,220,220,0.5)",
        strokeColor : "rgba(0,0,0,1)",
        pointColor : "rgba(220,220,220,1)",
        pointStrokeColor : "#000",
        data : dataOne
      },
      {
        fillColor : "rgba(220,220,220,0.5)",
        strokeColor : "rgba(220,220,220,1)",
        pointColor : "rgba(220,220,220,1)",
        pointStrokeColor : "#fff",
        data : dataTwo
      }
    ]
    @set('datasetOne', graphdata)
    @set('datasetOne', [])
    @set('datasetOne', graphdata)



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

      @setProperties('year': year, 'month': month)
      @transitionToRoute('weather', year, @strPad(month))

    goNext: ->
      month = parseInt(@get('month')) + 1
      year  = parseInt(@get('year'))

      if(month == 13)
        month = 1
        year += 1

      @setProperties('year': year, 'month': month)
      @transitionToRoute('weather', year, @strPad(month))
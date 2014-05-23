App.WeatherController = Ember.Controller.extend
  
  monthList: [
    'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli',
    'August', 'September', 'Oktober', 'November', 'Dezember'
  ]

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
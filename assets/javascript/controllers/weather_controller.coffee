App.WeatherController = Ember.Controller.extend
  
  year: 0

  blip: (->
    console.log @get 'year'
  ).property('year')

  currentMonth: (->
    monthList = [
      'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli',
      'August', 'September', 'Oktober', 'November', 'Dezember'
    ]
    monthList[(parseInt(@get('month'))-1)]+" &bull; "+@get('year')
  ).property('year', 'month')

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
App.IndexRoute = Ember.Route.extend
  beforeModel: ->
    @transitionTo 'weather', @getCurrentYear(), @getCurrentMonth()

  getCurrentYear: ->
    currentDate  = new Date()
    currentDate.getFullYear()

  getCurrentMonth: ->
    currentDate  = new Date()
    currentMonth = currentDate.getMonth() + 1
    currentMonth = "0"+currentMonth if currentMonth < 10
    currentMonth
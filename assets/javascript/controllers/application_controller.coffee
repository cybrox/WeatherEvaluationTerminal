App.ApplicationController = Ember.Controller.extend
  
  init: ->
    $.getJSON "api/get/notification/", (payload) =>
      @set('newWarnings', payload.data.length)

    @_super()

  newWarnings: 0
  hasWarnings: (->
    @get('newWarnings') > 0
  ).property('newWarnings')
  textWarnings: (->
    if(@get('newWarnings') == 1) then 'Warnung' else 'Warnungen'
  ).property('newWarnings')
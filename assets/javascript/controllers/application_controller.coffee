App.ApplicationController = Ember.Controller.extend
  newWarnings: (->
    10
  ).property()

  hasWarnings: (->
    @get('newWarnings') > 0
  ).property('newWarnings')
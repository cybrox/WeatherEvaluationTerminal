App.NotificationsRoute = Ember.Route.extend
  model: ->
    $.getJSON "api/get/notification/", (payload) =>
      payload
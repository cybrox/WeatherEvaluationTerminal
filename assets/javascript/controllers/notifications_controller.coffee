App.NotificationsController = Ember.Controller.extend
  actions:
    markRead: (uid) ->
      $.getJSON "api/set/notification/"+uid, (payload) =>
        if payload.success != true then alert "something went wrong."
        else $('#'+uid).fadeOut(300)
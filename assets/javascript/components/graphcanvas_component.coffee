App.GraphCanvasComponent = Ember.Component.extend
  tagName: 'canvas'

  didInsertElement: ->
    canvas = @get('element')
    jqcanv = $(canvas)
    context = canvas.getContext('2d')
    canvas.width = jqcanv.parent().width()
    canvas.height = jqcanv.parent().height()

    data = {
      labels: @generateLables()
      datasets: @get('data')
    }

    chart = new Chart(context)[@get('type')](data)

  generateLables: ->
    lables = []
    if @get('range') == 'month'
      lables = [
        'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli',
        'August', 'September', 'Oktober', 'November', 'Dezember'
      ]

    if @get('range') == 'day'
      for h in [0...24]
        for m in [0...60] by 15
          lables.push @stp(h)+':'+@stp(m)

      # Remove some lables for space
      for i in [0..lables.length] by 2
        lables[(i-1)] = "" 

    lables

  stp: (num) ->
    num = "0"+num if parseInt(num) < 10
    num
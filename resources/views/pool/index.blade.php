@extends('layouts.app')

@section('title', 'Mining')

@section('content')

  <script>
    function sparkline(
      element,
      chartType,
      qty,
      height,
      interpolation,
      duration,
      interval,
      color
    ) {
      // Basic setup
      // ------------------------------

      // Define main variables
      var d3Container = d3.select(element),
        margin = { top: 0, right: 0, bottom: 0, left: 0 },
        width =
          d3Container.node().getBoundingClientRect().width -
          margin.left -
          margin.right,
        height = height - margin.top - margin.bottom;

      // Generate random data (for demo only)
      var data = [];
      for (var i = 0; i < qty; i++) {
        data.push(Math.floor(Math.random() * qty) + 5);
      }

      // Construct scales
      // ------------------------------

      // Horizontal
      var x = d3.scale.linear().range([0, width]);

      // Vertical
      var y = d3.scale.linear().range([height - 5, 5]);

      // Set input domains
      // ------------------------------

      // Horizontal
      x.domain([1, qty - 3]);

      // Vertical
      y.domain([0, qty]);

      // Construct chart layout
      // ------------------------------

      // Line
      var line = d3.svg
        .line()
        .interpolate(interpolation)
        .x(function (d, i) {
          return x(i);
        })
        .y(function (d, i) {
          return y(d);
        });

      // Area
      var area = d3.svg
        .area()
        .interpolate(interpolation)
        .x(function (d, i) {
          return x(i);
        })
        .y0(height)
        .y1(function (d) {
          return y(d);
        });

      // Create SVG
      // ------------------------------

      // Container
      var container = d3Container.append("svg");

      // SVG element
      var svg = container
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

      // Add mask for animation
      // ------------------------------

      // Add clip path
      var clip = svg
        .append("defs")
        .append("clipPath")
        .attr("id", function (d, i) {
          return "load-clip-" + element.substring(1);
        });

      // Add clip shape
      var clips = clip
        .append("rect")
        .attr("class", "load-clip")
        .attr("width", 0)
        .attr("height", height);

      // Animate mask
      clips.transition().duration(1000).ease("linear").attr("width", width);

      //
      // Append chart elements
      //

      // Main path
      var path = svg
        .append("g")
        .attr("clip-path", function (d, i) {
          return "url(#load-clip-" + element.substring(1) + ")";
        })
        .append("path")
        .datum(data)
        .attr("transform", "translate(" + x(0) + ",0)");

      // Add path based on chart type
      if (chartType == "area") {
        path.attr("d", area).attr("class", "d3-area").style("fill", color); // area
      } else {
        path
          .attr("d", line)
          .attr("class", "d3-line d3-line-medium")
          .style("stroke", color); // line
      }

      // Animate path
      path.style("opacity", 0).transition().duration(750).style("opacity", 1);

      // Set update interval. For demo only
      // ------------------------------

      setInterval(function () {
        // push a new data point onto the back
        data.push(Math.floor(Math.random() * qty) + 5);

        // pop the old data point off the front
        data.shift();

        update();
      }, interval);

      // Update random data. For demo only
      // ------------------------------

      function update() {
        // Redraw the path and slide it to the left
        path
          .attr("transform", null)
          .transition()
          .duration(duration)
          .ease("linear")
          .attr("transform", "translate(" + x(0) + ",0)");

        // Update path type
        if (chartType == "area") {
          path.attr("d", area).attr("class", "d3-area").style("fill", color);
        } else {
          path
            .attr("d", line)
            .attr("class", "d3-line d3-line-medium")
            .style("stroke", color);
        }
      }

      // Resize chart
      // ------------------------------

      // Call function on window resize
      $(window).on("resize", resizeSparklines);

      // Call function on sidebar width change
      $(document).on("click", ".sidebar-control", resizeSparklines);

      // Resize function
      //
      // Since D3 doesn't support SVG resize by default,
      // we need to manually specify parts of the graph that need to
      // be updated on window resize
      function resizeSparklines() {
        // Layout variables
        width =
          d3Container.node().getBoundingClientRect().width -
          margin.left -
          margin.right;

        // Layout
        // -------------------------

        // Main svg width
        container.attr("width", width + margin.left + margin.right);

        // Width of appended group
        svg.attr("width", width + margin.left + margin.right);

        // Horizontal range
        x.range([0, width]);

        // Chart elements
        // -------------------------

        // Clip mask
        clips.attr("width", width);

        // Line
        svg.select(".d3-line").attr("d", line);

        // Area
        svg.select(".d3-area").attr("d", area);
      }
    }

    function progressCounter(
      element,
      radius,
      border,
      color,
      end,
      iconClass,
      textTitle,
      textAverage
    ) {
      // Basic setup
      // ------------------------------

      // Main variables
      var d3Container = d3.select(element),
        startPercent = 0,
        iconSize = 32,
        endPercent = end,
        twoPi = Math.PI * 2,
        formatPercent = d3.format(".0%"),
        boxSize = radius * 2;

      // Values count
      var count = Math.abs((endPercent - startPercent) / 0.01);

      // Values step
      var step = endPercent < startPercent ? -0.01 : 0.01;

      // Create chart
      // ------------------------------

      // Add SVG element
      var container = d3Container.append("svg");

      // Add SVG group
      var svg = container
        .attr("width", boxSize)
        .attr("height", boxSize)
        .append("g")
        .attr("transform", "translate(" + boxSize / 2 + "," + boxSize / 2 + ")");

      // Construct chart layout
      // ------------------------------

      // Arc
      var arc = d3.svg
        .arc()
        .startAngle(0)
        .innerRadius(radius)
        .outerRadius(radius - border);

      //
      // Append chart elements
      //

      // Paths
      // ------------------------------

      // Background path
      svg
        .append("path")
        .attr("class", "d3-progress-background")
        .attr("d", arc.endAngle(twoPi))
        .style("fill", "#eee");

      // Foreground path
      var foreground = svg
        .append("path")
        .attr("class", "d3-progress-foreground")
        .attr("filter", "url(#blur)")
        .style("fill", color)
        .style("stroke", color);

      // Front path
      var front = svg
        .append("path")
        .attr("class", "d3-progress-front")
        .style("fill", color)
        .style("fill-opacity", 1);

      // Text
      // ------------------------------

      // daily_profit text value
      var numberText = d3
        .select(element)
        .append("h2")
        .attr("class", "mt-15 mb-5");

      // Icon
      d3.select(element)
        .append("i")
        .attr("class", iconClass + " counter-icon")
        .attr("style", "top: " + (boxSize - iconSize) / 2 + "px");

      // Title
      d3.select(element).append("div").text(textTitle);

      // Subtitle
      d3.select(element)
        .append("div")
        .attr("class", "text-size-small text-muted")
        .text(textAverage);

      // Animation
      // ------------------------------

      // Animate path
      function updateProgress(progress) {
        foreground.attr("d", arc.endAngle(twoPi * progress));
        front.attr("d", arc.endAngle(twoPi * progress));
        numberText.text(formatPercent(progress));
      }

      // Animate text
      var progress = startPercent;
      (function loops() {
        updateProgress(progress);
        if (count > 0) {
          count--;
          progress += step;
          setTimeout(loops, 10);
        }
      })();
    }
  </script>
  <script type="text/javascript" src="/Public/assets/js/plugins/ui/moment/moment.min.js"></script>
  <script type="text/javascript" src="/Public/assets/js/plugins/pickers/daterangepicker.js"></script>

  <script type="text/javascript" src="/Public/assets/js/plugins/visualization/d3/d3.min.js"></script>
  <script type="text/javascript" src="/Public/assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
  <script type="text/javascript" src="/Public/assets/js/pages/dashboard.js"></script>
  <div class="page-top-banner">
    <div class="filter" style="
        background-image: url('/Public/template/epsilon/img/redesign/slider/filter2-min.png');
      ">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-8 mt-3">
            <h1>{{ \App\Models\Setting::get('site_name', 'Website Name') }}</h1>
            <h2></h2>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="container">
    <div class="row mt-3 mb-3">
      <div class="col-12 col-md-6 order-2 order-md-1">
        <div class="page-title-content d-flex align-items-start mt-2">
        </div>
      </div>

      <div class="col-12 col-md-6 order-1 order-md-2 float-right">
        <ul class="text-right breadcrumbs list-unstyle">
          <li class="btn btn-primary btn-sm active">Pool Home</li>
          
          <li>
            <a href="{{ route('pool.myMachines') }}" class="btn btn-primary btn-sm">My Machines</a>
          </li>
          <li>
            <a href="{{ route('pool.myRewards') }}" class="btn btn-primary btn-sm">My Rewards</a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Progress counters -->
    <div class="row">
      @foreach($pools as $pool)
      <div class="col-md-6 col-sm-12 col-md-4 mb-4">
        <!-- Current server load -->
        <div class="card">
          <div class="card-body" style="background-color: #2e2e2e !important">
            <div class="mine_img">
              <img class="" src="{{ $pool->logo_url ?? '/Upload/pool/60818c927e5e1.png' }}" width="40px" height="40px" />
            </div>
            <div class="row ml-1">
              <div class="col-xs-4">
                <div class="text-size-mini text-muted ml-3 text-white">
                  Rent Price </div>
                <h5 class="text-semibold no-margin ml-3 text-warning">
                  {{ $pool->price }} LTC </h5>
              </div>

              <div class="col-xs-4">
                <div class="text-size-mini text-muted ml-3 text-white">
                  Available Machines </div>
                <h5 class="text-semibold no-margin ml-3 text-warning">
                  {{ $pool->available }}/{{ $pool->total }} </h5>
              </div>

              <div class="col-xs-4">
                <div class="text-size-mini text-muted ml-3 text-white">
                  Daily Reward </div>
                <h5 class="text-semibold no-margin ml-3 text-warning">
                  {{ $pool->daily_reward }} LTC </h5>
              </div>
            </div>
            <div id="mine-load-{{ $pool->id }}"></div>

            <div class="botright">
              @auth
              <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#Buyingform_{{ $pool->id }}">
                Rent now
              </button>
              @else
              <a href="{{ url('/login') }}" class="btn btn-sm btn-primary float-right ">Login to Rent</a>
              @endauth
              
            </div>
            
            @auth

             <!-- Modal for this pool -->
            <div class="modal fade" id="Buyingform_{{ $pool->id }}" tabindex="-1" role="dialog" aria-labelledby="Buyingform_Label_{{ $pool->id }}"
              aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="Buyingform_Label_{{ $pool->id }}">
                      {{ $pool->name }} - Mining Pool </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{ route('pool.rent', $pool->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-8">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-6">
                                <input type="text" id="investtype" name="investtype_{{ $pool->id }}" 
                                  value="Available {{ $pool->available }}" disabled 
                                  placeholder="Available {{ $pool->available }}" class="form-control" />
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <div class="row">
                              <div class="col-6">
                                <span class="small"><b>Price </b> </span>
                                <label class="text-right small col-xs-7"><b>Available </b>
                              </div>

                              <div class="col-6">
                                <span class="small text-right">{{ $pool->price }} LTC</span>
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <div class="row">
                              <div class="col-6">
                                <input type="number" class="form-control" placeholder="Please enter the amount" 
                                  id="pool_box{{ $pool->id }}" name="amount" value="1" 
                                  min="1" max="{{ $pool->available }}" required />
                              </div>

                              <div class="col-6">
                                <span class="input-group-btn">
                                  <button class="btn btn-info" type="button" onclick="plusone({{ $pool->id }})">
                                    <small>+ 1</small>
                                  </button>
                                </span>
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <div class="row">
                              <div class="col-6">
                                <span class="small"><b id="total_cost_{{ $pool->id }}">{{ $pool->price }}</b></span>
                              </div>

                              <div class="col-6">
                                <span class="small text-right">LTC</span>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-6">
                                <span class="small"><b>Duration</b></span>
                              </div>
                              <div class="col-6">
                                <span class="small text-right">{{ $pool->duration_days }} days</span>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-6">
                                <span class="small"><b>Daily Reward</b></span>
                              </div>
                              <div class="col-6">
                                <span class="small text-right">{{ $pool->daily_reward }} LTC</span>
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <div class="row">
                              <div class="col-6">
                                <span class="small"><b>Total Reward</b></span>
                              </div>
                              <div class="col-6">
                                <span class="small text-right">{{ $pool->daily_reward * $pool->duration_days }} LTC</span>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-6">
                                <span class="small"> <b>Power</b> </span>
                              </div>
                              <div class="col-6">
                                <span class="small text-right">{{ $pool->power }}</span>
                              </div>
                            </div>
                          </div>
                          <div class="">
                            <button type="submit" class="btn btn-primary">
                              Confirm Purchase
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                      Close
                    </button>
                  </div>
                </div>
              </div>
            </div>
                
            @endauth
           
          </div>
          <div class="svg-center" id="prog-round-{{ $pool->id }}"></div>
        </div>
        <!-- /current server load -->
      </div>
      <script>
        // Initialize sparkline for this pool
        sparkline(
          "#mine-load-{{ $pool->id }}",
          "area",
          30,
          50,
          "basis",
          750,
          2000,
          "rgba(255,255,255,0.5)"
        );

        // Calculate total cost when amount changes
        document.getElementById('pool_box{{ $pool->id }}').addEventListener('input', function() {
          const amount = parseInt(this.value) || 0;
          const price = {{ $pool->price }};
          const totalCost = amount * price;
          document.getElementById('total_cost_{{ $pool->id }}').textContent = totalCost.toFixed(8);
        });
      </script>
      @endforeach
    </div>
    <!-- /progress counters -->

    <style>
      .mine_img img {
        position: absolute;
        overflow: hidden !important;
        left: 54px;
        opacity: 52%;
        bottom: 25px;
      }

      .botright button {
        position: absolute;
        overflow: hidden !important;
        right: 69px;
        bottom: 25px;
      }

      .popular-button {
        background-color: #1e90ff !important;
        width: 16%;
        padding: 0px 4px;
        position: absolute;
        right: 15px;
        top: 0;
      }

      @media (max-width: 768px) {
        .mine_img img {
          left: 20px;
          bottom: 15px;
        }
        
        .botright button {
          right: 20px;
          bottom: 15px;
        }
      }
    </style>

    <!-- Pools Table -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body table-responsive">
            <table class="table text-center ">
              <thead>
                <tr>
                  <th>Pool</th>
                  <th>Price</th>
                  <th>Availability</th>
                  <th class="hide-mobile">Daily Reward</th>
                  <th class="hide-mobile">Duration</th>
                  <th class="hide-mobile">Total Reward</th>
                  <th class="hide-mobile">User Limit</th>
                  <th>Power</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pools as $pool)
                <tr>
                  <td>{{ $pool->name }}</td>
                  <td>{{ $pool->price }} LTC</td>
                  <td>{{ $pool->available }}/{{ $pool->total }}</td>
                  <td class="hide-mobile">{{ $pool->daily_reward }} LTC</td>
                  <td class="hide-mobile">{{ $pool->duration_days }} Days</td>
                  <td class="hide-mobile">{{ $pool->daily_reward * $pool->duration_days }} LTC</td>
                  <td class="hide-mobile">{{ $pool->user_limit > 0 ? $pool->user_limit : 'Unlimited' }}</td>
                  <td>{{ $pool->power }}</td>
                  <td>
                    @auth
                      <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#Buyingform_{{ $pool->id }}">
                        Rent now<i class="fa fa-flash"></i>
                      </button>
                    @else
                      <a href="{{ url('/login') }}" class="btn btn-sm btn-primary">Login to Rent</a>
                    @endauth
                    
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="card-footer">
            <div class="pages">
              <!-- Pagination can be added here if needed -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function plusone(id) {
      var input = document.getElementById('pool_box' + id);
      var max = parseInt(input.getAttribute('max'));
      var currentVal = parseInt(input.value) || 0;
      
      if (currentVal < max) {
        input.value = currentVal + 1;
        
        // Trigger the input event to update total cost
        var event = new Event('input');
        input.dispatchEvent(event);
      }
    }

    // Initialize all sparklines when page loads
    document.addEventListener('DOMContentLoaded', function() {
      @foreach($pools as $pool)
        // Sparkline already initialized above
      @endforeach
    });

    // Display messages
    @if(session('success'))
      alert('{{ session('success') }}');
    @endif

    @if(session('error'))
      alert('{{ session('error') }}');
    @endif
  </script>

  <style>
    @media only screen and (max-width: 540px) {
      .mobile_hide {
        display: none;
      }

      .hide-mobile {
        display: none;
      }

      #mining_machines td {
        display: none;
      }

      #mining_machines td:first-child,
      #mining_machines td:last-child,
      #mining_machines th:first-child,
      #mining_machines th:last-child {
        display: table-cell;
      }

      #mining_machines td,
      #mining_machines th {
        display: none;
      }
    }
  </style>

@endsection
<?php
$page_title = 'Schedule - Eventia Bootstrap Template';
$body_class = 'schedule-page';
$current_page = 'schedule';
$base_url = '/'; include __DIR__ . '/../includes/header.php';
?>

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/events/showcase-5.webp);">
      <div class="container position-relative">
        <h1>Schedule</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Schedule</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Schedule 2 Section -->
    <section id="schedule-2" class="schedule-2 section">

      <!-- Section Title -->
      <div class="container section-title">
        <span class="subtitle">Schedule</span>
        <h2>Schedule</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium totam rem aperiam</p>
      </div><!-- End Section Title -->

      <div class="container">

        <ul class="schedule-tabs nav nav-tabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="tab-btn nav-link active" data-bs-toggle="tab" data-bs-target="#schedule-2-tab-1" type="button" role="tab" aria-selected="true">
              <span class="day-label">Day 01</span>
              Sunday
              <span class="day-date">March 15, 2026</span>
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="tab-btn nav-link" data-bs-toggle="tab" data-bs-target="#schedule-2-tab-2" type="button" role="tab" aria-selected="false">
              <span class="day-label">Day 02</span>
              Monday
              <span class="day-date">March 16, 2026</span>
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="tab-btn nav-link" data-bs-toggle="tab" data-bs-target="#schedule-2-tab-3" type="button" role="tab" aria-selected="false">
              <span class="day-label">Day 03</span>
              Tuesday
              <span class="day-date">March 17, 2026</span>
            </button>
          </li>
        </ul>

        <div class="tab-content">

          <div class="tab-pane fade show active" id="schedule-2-tab-1" role="tabpanel">

            <div class="schedule-item">
              <div class="time-slot">
                <span class="time">09:00 AM</span>
                <span class="duration">45 min</span>
              </div>
              <div class="item-content">
                <h4><a href="schedule/">Opening Keynote: Future of Innovation</a></h4>
                <div class="location"><i class="bi bi-geo-alt"></i> Main Hall A</div>
                <p>Perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium totam rem aperiam.</p>
              </div>
              <div class="speaker-info">
                <img src="assets/img/events/speaker-1.webp" alt="Speaker">
                <div class="speaker-details">
                  <span class="name">Marcus Reynolds</span>
                  <span class="role">CEO, TechVision</span>
                </div>
              </div>
            </div><!-- End Schedule Item -->

            <div class="schedule-item">
              <div class="time-slot">
                <span class="time">10:30 AM</span>
                <span class="duration">60 min</span>
              </div>
              <div class="item-content">
                <h4><a href="schedule/">Workshop: Building Scalable Systems</a></h4>
                <div class="location"><i class="bi bi-geo-alt"></i> Workshop Room 1</div>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque.</p>
              </div>
              <div class="speaker-info">
                <img src="assets/img/events/speaker-3.webp" alt="Speaker">
                <div class="speaker-details">
                  <span class="name">Elena Martinez</span>
                  <span class="role">CTO, CloudScale</span>
                </div>
              </div>
            </div><!-- End Schedule Item -->

            <div class="schedule-item">
              <div class="time-slot">
                <span class="time">02:00 PM</span>
                <span class="duration">45 min</span>
              </div>
              <div class="item-content">
                <h4><a href="schedule/">Panel: Digital Transformation Strategies</a></h4>
                <div class="location"><i class="bi bi-geo-alt"></i> Conference Room B</div>
                <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.</p>
              </div>
              <div class="speaker-info">
                <img src="assets/img/events/speaker-5.webp" alt="Speaker">
                <div class="speaker-details">
                  <span class="name">David Chen</span>
                  <span class="role">Director, InnovateCo</span>
                </div>
              </div>
            </div><!-- End Schedule Item -->

          </div><!-- End Tab Pane 1 -->

          <div class="tab-pane fade" id="schedule-2-tab-2" role="tabpanel">

            <div class="schedule-item">
              <div class="time-slot">
                <span class="time">09:30 AM</span>
                <span class="duration">50 min</span>
              </div>
              <div class="item-content">
                <h4><a href="schedule/">AI and Machine Learning Applications</a></h4>
                <div class="location"><i class="bi bi-geo-alt"></i> Main Hall A</div>
                <p>Ut enim ad minima veniam quis nostrum exercitationem ullam corporis suscipit laboriosam.</p>
              </div>
              <div class="speaker-info">
                <img src="assets/img/events/speaker-7.webp" alt="Speaker">
                <div class="speaker-details">
                  <span class="name">Sarah Williams</span>
                  <span class="role">AI Lead, DataMind</span>
                </div>
              </div>
            </div><!-- End Schedule Item -->

            <div class="schedule-item">
              <div class="time-slot">
                <span class="time">11:00 AM</span>
                <span class="duration">90 min</span>
              </div>
              <div class="item-content">
                <h4><a href="schedule/">Hands-on: Cloud Architecture Workshop</a></h4>
                <div class="location"><i class="bi bi-geo-alt"></i> Workshop Room 2</div>
                <p>Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae.</p>
              </div>
              <div class="speaker-info">
                <img src="assets/img/events/speaker-9.webp" alt="Speaker">
                <div class="speaker-details">
                  <span class="name">James Anderson</span>
                  <span class="role">Architect, AWS</span>
                </div>
              </div>
            </div><!-- End Schedule Item -->

            <div class="schedule-item">
              <div class="time-slot">
                <span class="time">03:00 PM</span>
                <span class="duration">45 min</span>
              </div>
              <div class="item-content">
                <h4><a href="schedule/">Networking Session &amp; Refreshments</a></h4>
                <div class="location"><i class="bi bi-geo-alt"></i> Networking Lounge</div>
                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium.</p>
              </div>
              <div class="speaker-info">
                <img src="assets/img/events/speaker-11.webp" alt="Speaker">
                <div class="speaker-details">
                  <span class="name">Lisa Thompson</span>
                  <span class="role">Event Coordinator</span>
                </div>
              </div>
            </div><!-- End Schedule Item -->

          </div><!-- End Tab Pane 2 -->

          <div class="tab-pane fade" id="schedule-2-tab-3" role="tabpanel">

            <div class="schedule-item">
              <div class="time-slot">
                <span class="time">10:00 AM</span>
                <span class="duration">60 min</span>
              </div>
              <div class="item-content">
                <h4><a href="schedule/">Startup Pitch Competition Finals</a></h4>
                <div class="location"><i class="bi bi-geo-alt"></i> Main Hall A</div>
                <p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet.</p>
              </div>
              <div class="speaker-info">
                <img src="assets/img/events/speaker-13.webp" alt="Speaker">
                <div class="speaker-details">
                  <span class="name">Michael Brown</span>
                  <span class="role">Investor, VentureX</span>
                </div>
              </div>
            </div><!-- End Schedule Item -->

            <div class="schedule-item">
              <div class="time-slot">
                <span class="time">01:30 PM</span>
                <span class="duration">45 min</span>
              </div>
              <div class="item-content">
                <h4><a href="schedule/">Closing Keynote: Vision for Tomorrow</a></h4>
                <div class="location"><i class="bi bi-geo-alt"></i> Main Hall A</div>
                <p>Nam libero tempore cum soluta nobis est eligendi optio cumque nihil impedit quo minus.</p>
              </div>
              <div class="speaker-info">
                <img src="assets/img/events/speaker-15.webp" alt="Speaker">
                <div class="speaker-details">
                  <span class="name">Rebecca Foster</span>
                  <span class="role">Futurist, TrendLab</span>
                </div>
              </div>
            </div><!-- End Schedule Item -->

            <div class="schedule-item">
              <div class="time-slot">
                <span class="time">04:00 PM</span>
                <span class="duration">120 min</span>
              </div>
              <div class="item-content">
                <h4><a href="schedule/">Awards Ceremony &amp; Closing Party</a></h4>
                <div class="location"><i class="bi bi-geo-alt"></i> Grand Ballroom</div>
                <p>Et harum quidem rerum facilis est et expedita distinctio nam libero tempore.</p>
              </div>
              <div class="speaker-info">
                <img src="assets/img/events/speaker-2.webp" alt="Speaker">
                <div class="speaker-details">
                  <span class="name">Event Team</span>
                  <span class="role">Eventia Organizers</span>
                </div>
              </div>
            </div><!-- End Schedule Item -->

          </div><!-- End Tab Pane 3 -->

        </div><!-- End Tab Content -->

      </div>

    </section><!-- /Schedule 2 Section -->

<?php include __DIR__ . '/../includes/footer.php'; ?>

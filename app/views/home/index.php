   <!-- Slider Section -->
   <section id="slider" class="slider section dark-background">
       <div class="container" data-aos="fade-up" data-aos-delay="100">
           <div class="swiper init-swiper">
               <script type="application/json" class="swiper-config">
                   {
                       "loop": true,
                       "speed": 600,
                       "autoplay": {
                           "delay": 5000
                       },
                       "slidesPerView": "auto",
                       "centeredSlides": true,
                       "pagination": {
                           "el": ".swiper-pagination",
                           "type": "bullets",
                           "clickable": true
                       },
                       "navigation": {
                           "nextEl": ".swiper-button-next",
                           "prevEl": ".swiper-button-prev"
                       }
                   }
               </script>

               <div class="swiper-wrapper">
                   <?php foreach ($featuredPosts as $post): ?>
                       <div class="swiper-slide" style="background-image: url('<?= htmlspecialchars($post['image_path']) ?>');">
                           <div class="content">
                               <h2><a href="/post/show/<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
                               <p><?= htmlspecialchars(substr($post['content'], 0, 150)) ?>...</p>
                           </div>
                       </div>
                   <?php endforeach; ?>
               </div>

               <div class="swiper-button-next"></div>
               <div class="swiper-button-prev"></div>
               <div class="swiper-pagination"></div>
           </div>
       </div>
   </section><!-- /Slider Section -->
   <div class="container section-title">
       <div class="section-title-container d-flex align-items-center justify-content-between">
           <h2>Articles récents</h2>
           <p><a href="/post">Tous les articles</a></p>
       </div>
   </div>
   <div class="container">
       <div class="row">
           <div class="col-md-8 mb-2 mt-2">
               <div class="row">
                   <a href="/post/create" class="btn btn-primary mb-3">Créer un nouvel article</a>
                   <?php if (!empty($recentPosts)): ?>
                       <?php foreach ($recentPosts as $post): ?>
                           <?php include __DIR__ . '/../partial/articles_row_partial.php'; ?>
                       <?php endforeach; ?>
                   <?php else: ?>
                       <p>Aucun article disponible pour moment.</p>
                   <?php endif; ?>
               </div>
           </div>
           <!-- sidebar Section -->
           <div class="col-md-4 mb-2 mt-3">
               <?php include __DIR__ . '/../partial/sidebar_search_partial.php'; ?>
               <?php include __DIR__ . '/../partial/sidebar_category_partial.php'; ?>
               <?php include __DIR__ . '/../partial/sidebar_popular_post_partial.php'; ?>
           </div>
           <div class="section-title">
               <div class="section-title-container d-flex align-items-center justify-content-between">
                   <h2>Blogeurs récents</h2>
                   <p><a href="/user">Tous les Blogeurs</a></p>
               </div>
           </div>
           <?php include __DIR__ . '/../partial/author_profil_partial.php'; ?>
       </div>
   </div>
   </main>
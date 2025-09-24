<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Digital Marketing Course
        $digitalMarketing = Course::create([
            'title' => 'Digital Marketing Mastery',
            'description' => 'Learn the fundamentals of digital marketing including SEO, social media, email marketing, and paid advertising strategies.',
            'status' => true,
        ]);

        // Module 1: Introduction to Digital Marketing
        $module1 = Module::create([
            'course_id' => $digitalMarketing->id,
            'title' => 'Introduction to Digital Marketing',
            'order' => 1,
        ]);

        // Lessons for Module 1
        Lesson::create([
            'module_id' => $module1->id,
            'title' => 'What is Digital Marketing?',
            'content' => 'Digital marketing is the promotion of brands using the internet and other forms of digital communication to connect with potential customers. This includes not only email, social media, and web-based advertising, but also text and multimedia messages as a marketing channel.',
            'video_url' => 'https://www.youtube.com/watch?v=9noNZ4wsmUg',
            'order' => 1,
        ]);

        Lesson::create([
            'module_id' => $module1->id,
            'title' => 'Digital Marketing vs Traditional Marketing',
            'content' => 'Traditional marketing uses offline channels to reach customers, while digital marketing uses online channels. Digital marketing offers better targeting, measurability, and cost-effectiveness compared to traditional methods.',
            'video_url' => 'https://www.youtube.com/watch?v=9noNZ4wsmUg',
            'order' => 2,
        ]);

        Lesson::create([
            'module_id' => $module1->id,
            'title' => 'Digital Marketing Channels Overview',
            'content' => 'The main digital marketing channels include: Search Engine Optimization (SEO), Social Media Marketing, Email Marketing, Content Marketing, Pay-Per-Click (PPC) Advertising, Affiliate Marketing, and Mobile Marketing.',
            'video_url' => 'https://www.youtube.com/watch?v=9noNZ4wsmUg',
            'order' => 3,
        ]);

        // Module 2: SEO Fundamentals
        $module2 = Module::create([
            'course_id' => $digitalMarketing->id,
            'title' => 'SEO Fundamentals',
            'order' => 2,
        ]);

        // Lessons for Module 2
        Lesson::create([
            'module_id' => $module2->id,
            'title' => 'What is SEO?',
            'content' => 'Search Engine Optimization (SEO) is the practice of optimizing websites to rank higher in search engine results pages (SERPs). The goal is to increase organic (non-paid) traffic to your website.',
            'video_url' => 'https://www.youtube.com/watch?v=9noNZ4wsmUg',
            'order' => 1,
        ]);

        Lesson::create([
            'module_id' => $module2->id,
            'title' => 'Keyword Research',
            'content' => 'Keyword research is the process of finding and analyzing search terms that people enter into search engines. It helps you understand what your target audience is searching for and how competitive those terms are.',
            'video_url' => 'https://www.youtube.com/watch?v=9noNZ4wsmUg',
            'order' => 2,
        ]);

        Lesson::create([
            'module_id' => $module2->id,
            'title' => 'On-Page SEO',
            'content' => 'On-page SEO refers to optimization techniques that can be applied to individual web pages to improve their search engine rankings. This includes optimizing title tags, meta descriptions, headers, content, and images.',
            'video_url' => 'https://www.youtube.com/watch?v=9noNZ4wsmUg',
            'order' => 3,
        ]);

        // Module 3: Social Media Marketing
        $module3 = Module::create([
            'course_id' => $digitalMarketing->id,
            'title' => 'Social Media Marketing',
            'order' => 3,
        ]);

        // Lessons for Module 3
        Lesson::create([
            'module_id' => $module3->id,
            'title' => 'Social Media Strategy',
            'content' => 'A social media strategy is a plan for how you will use social media to achieve your business goals. It includes defining your target audience, choosing the right platforms, creating content, and measuring success.',
            'video_url' => 'https://www.youtube.com/watch?v=9noNZ4wsmUg',
            'order' => 1,
        ]);

        Lesson::create([
            'module_id' => $module3->id,
            'title' => 'Content Creation for Social Media',
            'content' => 'Creating engaging content for social media requires understanding your audience, choosing the right format, maintaining consistency, and encouraging interaction. Visual content typically performs better than text-only posts.',
            'video_url' => 'https://www.youtube.com/watch?v=9noNZ4wsmUg',
            'order' => 2,
        ]);

        // Create Affiliate Marketing Course
        $affiliateMarketing = Course::create([
            'title' => 'Affiliate Marketing Success',
            'description' => 'Master the art of affiliate marketing and learn how to build a profitable affiliate business from scratch.',
            'status' => true,
        ]);

        // Module 1: Affiliate Marketing Basics
        $affiliateModule1 = Module::create([
            'course_id' => $affiliateMarketing->id,
            'title' => 'Affiliate Marketing Basics',
            'order' => 1,
        ]);

        // Lessons for Affiliate Module 1
        Lesson::create([
            'module_id' => $affiliateModule1->id,
            'title' => 'What is Affiliate Marketing?',
            'content' => 'Affiliate marketing is a performance-based marketing strategy where businesses reward affiliates for each customer brought by the affiliate\'s own marketing efforts. It\'s a win-win situation for both parties.',
            'video_url' => 'https://www.youtube.com/watch?v=9noNZ4wsmUg',
            'order' => 1,
        ]);

        Lesson::create([
            'module_id' => $affiliateModule1->id,
            'title' => 'How Affiliate Marketing Works',
            'content' => 'The affiliate marketing process involves: 1) Joining an affiliate program, 2) Getting your unique affiliate link, 3) Promoting the product/service, 4) Earning commission when someone makes a purchase through your link.',
            'video_url' => 'https://www.youtube.com/watch?v=9noNZ4wsmUg',
            'order' => 2,
        ]);

        // Module 2: Building Your Affiliate Business
        $affiliateModule2 = Module::create([
            'course_id' => $affiliateMarketing->id,
            'title' => 'Building Your Affiliate Business',
            'order' => 2,
        ]);

        // Lessons for Affiliate Module 2
        Lesson::create([
            'module_id' => $affiliateModule2->id,
            'title' => 'Choosing the Right Niche',
            'content' => 'Selecting the right niche is crucial for affiliate marketing success. Choose a niche you\'re passionate about, that has a good market size, and where you can provide value to your audience.',
            'video_url' => 'https://www.youtube.com/watch?v=9noNZ4wsmUg',
            'order' => 1,
        ]);

        Lesson::create([
            'module_id' => $affiliateModule2->id,
            'title' => 'Building Your Website',
            'content' => 'Your website is your home base for affiliate marketing. It should be professional, user-friendly, mobile-responsive, and optimized for search engines. Include valuable content that helps your audience.',
            'video_url' => 'https://www.youtube.com/watch?v=9noNZ4wsmUg',
            'order' => 2,
        ]);

        // Create E-commerce Course
        $ecommerce = Course::create([
            'title' => 'E-commerce Fundamentals',
            'description' => 'Learn how to start and grow a successful online store with proven e-commerce strategies and tactics.',
            'status' => true,
        ]);

        // Module 1: E-commerce Basics
        $ecommerceModule1 = Module::create([
            'course_id' => $ecommerce->id,
            'title' => 'E-commerce Basics',
            'order' => 1,
        ]);

        // Lessons for E-commerce Module 1
        Lesson::create([
            'module_id' => $ecommerceModule1->id,
            'title' => 'Introduction to E-commerce',
            'content' => 'E-commerce (electronic commerce) is the buying and selling of goods and services over the internet. It includes B2B, B2C, C2C, and C2B business models.',
            'video_url' => 'https://www.youtube.com/watch?v=9noNZ4wsmUg',
            'order' => 1,
        ]);

        Lesson::create([
            'module_id' => $ecommerceModule1->id,
            'title' => 'E-commerce Business Models',
            'content' => 'Common e-commerce business models include: 1) B2C (Business to Consumer), 2) B2B (Business to Business), 3) C2C (Consumer to Consumer), 4) C2B (Consumer to Business), and 5) B2G (Business to Government).',
            'video_url' => 'https://www.youtube.com/watch?v=9noNZ4wsmUg',
            'order' => 2,
        ]);

        $this->command->info('Sample courses, modules, and lessons created successfully!');
    }
}
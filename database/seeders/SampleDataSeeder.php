<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostAction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users (password = "password" for all) ──────────────────────────
        $users = [
            ['name' => 'demo',      'email' => 'demo@blog.test'],
            ['name' => 'sarah_k',   'email' => 'sarah@blog.test'],
            ['name' => 'mike_dev',  'email' => 'mike@blog.test'],
            ['name' => 'priya.r',   'email' => 'priya@blog.test'],
        ];

        $created = [];
        foreach ($users as $u) {
            $created[$u['name']] = User::updateOrCreate(
                ['email' => $u['email']],
                ['name' => $u['name'], 'password' => 'password']
            );
        }

        // ── Posts ──────────────────────────────────────────────────────────
        $posts = [
            ['sarah_k',  'Getting Started with Laravel 12', "Laravel 12 brings a refined developer experience with cleaner defaults and a streamlined bootstrap process.\n\nIn this post we walk through setting up your first project, understanding the new application structure, and shipping a working page in minutes.\n\nThe framework continues to favor convention over configuration, so you spend less time on boilerplate and more time building features that matter.", 342],
            ['mike_dev', 'Why Tailwind CSS Changed How I Build UIs', "For years I fought with bloated stylesheets and naming conventions. Then Tailwind happened.\n\nUtility-first styling keeps everything in the markup where you can see it, kills dead CSS, and makes responsive design almost effortless.\n\nThis post covers my favorite patterns, the mental shift it requires, and when you should reach for a component instead.", 521],
            ['priya.r',  'A Practical Guide to REST API Design', "Good APIs feel obvious. Bad ones make you read the docs five times.\n\nWe cover resource naming, sensible status codes, pagination, versioning, and error shapes that clients can actually rely on.\n\nFollow these conventions and your consumers will thank you — including future you.", 287],
            ['demo',     'My First Post on BlogApp', "Hey everyone! This is my very first post here.\n\nI built this little blog to share thoughts on web development, design, and the occasional coffee recommendation.\n\nExcited to be part of the community — drop a comment and say hi!", 95],
            ['sarah_k',  'Understanding Database Indexes', "An index is just a sorted lookup structure — but used well it can turn a 2-second query into 2 milliseconds.\n\nWe explore B-tree indexes, composite keys, and the classic mistake of indexing everything.\n\nThe golden rule: index for how you read, not for how you write.", 410],
            ['mike_dev', 'Dark Mode Done Right', "Dark mode is more than inverting colors. Contrast, elevation, and muted accents all matter.\n\nThis post breaks down a token-based approach using CSS variables and the `dark:` variant, plus how to respect the user's system preference.\n\nYour eyes (and your users) will appreciate the effort.", 368],
            ['priya.r',  'Shipping Side Projects Without Burning Out', "Most side projects die in the 'almost done' phase. Here's how I keep mine alive.\n\nScope ruthlessly, ship something embarrassing early, and celebrate small wins. Momentum beats motivation every time.\n\nThe project you finished beats the perfect one you never launched.", 233],
            ['demo',     'Coffee and Code: A Love Story', "There's a reason developers and coffee are inseparable.\n\nIn this lighthearted post I rank my favorite brewing methods and pair each one with the kind of coding task it suits best.\n\nPour-over for deep focus, espresso for quick fixes — you get the idea.", 178],
        ];

        $postModels = [];
        foreach ($posts as $i => [$author, $title, $content, $views]) {
            $post = Post::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title'       => $title,
                    'content'     => $content,
                    'created_by'  => $created[$author]->id,
                    'views_count' => $views,
                ]
            );

            // created_at is not mass-assignable — set it directly for varied dates
            $post->forceFill([
                'created_at' => now()->subDays(count($posts) - $i)->subHours($i * 3),
            ])->saveQuietly();

            $postModels[] = $post;
        }

        // ── Likes & comments (respecting unique [post_id, user_id, type]) ────
        $sampleComments = [
            'This is exactly what I needed, thanks for sharing!',
            'Great write-up — bookmarked for later. 🔖',
            'I disagree slightly on one point, but solid overall.',
            'Clear and to the point. More posts like this please!',
            'Learned something new today. Appreciate it 🙌',
            'Been struggling with this, your explanation finally made it click.',
        ];

        $allUsers = array_values($created);

        foreach ($postModels as $idx => $post) {
            // Each user (except author) likes ~70% of posts
            foreach ($allUsers as $u) {
                if ($u->id === $post->created_by) {
                    continue;
                }
                if (($u->id + $idx) % 10 < 7) {
                    PostAction::firstOrCreate(
                        ['post_id' => $post->id, 'user_id' => $u->id, 'type' => 'like'],
                        []
                    );
                }
            }

            // A couple of users comment (one comment each, per the unique rule)
            $commenters = collect($allUsers)
                ->reject(fn ($u) => $u->id === $post->created_by)
                ->take(2 + ($idx % 2));

            foreach ($commenters as $ci => $u) {
                PostAction::firstOrCreate(
                    ['post_id' => $post->id, 'user_id' => $u->id, 'type' => 'comment'],
                    ['comment' => $sampleComments[($idx + $ci) % count($sampleComments)]]
                );
            }
        }

        $this->command->info('Sample data ready. Login with demo@blog.test / password');
    }
}

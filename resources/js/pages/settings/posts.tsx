import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { index as tasksIndex } from '@/routes/tasks';

type Post = {
    id: number;
    title: string;
    description: string;
    status: boolean;
    is_urgent: boolean;
    category?: {
        name: string;
    } | null;
};

export default function Posts({ tasks }: { tasks: Post[] }) {
    return (
        <>
            <Head title="Posts" />

            <h1 className="sr-only">Posts</h1>

            <div className="space-y-6">
                <div className="flex items-start justify-between gap-4">
                    <Heading
                        variant="small"
                        title="Posts"
                        description="Tasks you have posted"
                    />
                    <Button variant="outline" size="sm" asChild>
                        <Link href={tasksIndex()}>Task feed</Link>
                    </Button>
                </div>

                {tasks.length === 0 ? (
                    <div className="rounded-lg border border-dashed p-6 text-sm text-muted-foreground">
                        You have not posted any tasks yet.
                    </div>
                ) : (
                    <div className="space-y-3">
                        {tasks.map((task) => (
                            <Link
                                key={task.id}
                                href={`/tasks/${task.id}`}
                                className="block rounded-lg border p-4 transition-colors hover:bg-muted"
                            >
                                <div className="flex items-start justify-between gap-4">
                                    <h2 className="font-medium">{task.title}</h2>
                                    <span className="text-sm text-muted-foreground">
                                        {task.status ? 'Closed' : 'Open'}
                                    </span>
                                </div>
                                <p className="mt-2 text-sm text-muted-foreground">
                                    {task.description}
                                </p>
                                <span className="mt-3 inline-block text-xs text-muted-foreground">
                                    {task.category?.name ?? 'Unknown category'}
                                </span>
                            </Link>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}

Posts.layout = {
    breadcrumbs: [
        {
            title: 'Posts',
            href: '/settings/posts',
        },
    ],
};

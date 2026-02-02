<template>
  <AppLayout :title="post.title">
    <Container>
        <h1 class="text-2xl font-bold">{{ post.title }}</h1>
        <span class="block mt-1 text-sm text-gray-600">{{ relativeDate(props.post.created_at) }} ago by {{ post.user.name }}</span>
        <article class="mt-6">
            <pre class="whitespace-pre-wrap font-sans">{{ post.body }}</pre>
        </article>

        <div class="mt-12">
            <h2 class="text-xl font-semibold">Comments</h2>

            <form v-if="$page.props.auth.user" @submit.prevent="addComment" class="mt-4">
                <div>
                    <InputLabel for="body" class="sr-only">Comment</InputLabel>
                    <TextArea id="body" v-model="commentForm.body" rows="4" placeholder="Your words of wisdom..."></TextArea>
                    <InputError :message="commentForm.errors.body" class="mt-1"></InputError>
                </div>

                <PrimaryButton type="submit" class="mt-3" :disabled="commentForm.processing">Add Comment</PrimaryButton>
            </form>

            <ul class="divide-y mt-4">
                <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4">
                    <Comment :comment="comment" />
                </li>
            </ul>

            <Pagination :meta="comments.meta" :only="['comments']"/> 
        </div>
    </Container>
  </AppLayout>
</template>

<script setup>

import AppLayout from '@/Layouts/AppLayout.vue'
import Container from '@/Components/Container.vue'
import Pagination from '@/Components/Pagination.vue'
import Comment from '@/Components/Comment.vue'
import TextArea from '@/Components/TextArea.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { computed } from 'vue'
import { relativeDate } from '@/Utilities/date'
import { useForm } from '@inertiajs/vue3'

const props = defineProps(['post', 'comments']);

const commentForm = useForm({
    body: '',
});

const addComment = () => commentForm.post(route('posts.comments.store', props.post.id), {
    preserveScroll: true,
    onSuccess: () => commentForm.reset()
})

</script>


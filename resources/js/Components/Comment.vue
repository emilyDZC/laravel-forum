<template>
    <div class="sm:flex">
        <div class="mb-4 flex-shrink-0 sm:mb-0 sm:mr-4"></div>
        <img :src="comment.user.profile_photo_url" class="h-10 w-10 rounded-full mr-4"/>
        <!-- <img :src="comment.user.profile_photo_url" class="inline-block size-6 rounded-full ring-2 ring-white outline -outline-offset-1 outline-black/5"/> -->
        <div>
            <p class="mt-1 break-all">{{ comment.body }}</p>
            <span class="block pt-1 text-xs text-gray-600">By {{ comment.user.name }} {{ relativeDate(comment.created_at) }} ago</span>
            <div v-if="canDelete" class="mt-1">
                <form @submit.prevent="deleteComment">
                    <button>Delete</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { relativeDate } from '@/Utilities/date'
import { router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps(['comment'])

const deleteComment = () => router.delete(route('comments.destroy', props.comment.id), {
    preserveScroll: true
})

const canDelete = computed(() => props.comment.user.id === usePage().props.auth.user.id)

</script>
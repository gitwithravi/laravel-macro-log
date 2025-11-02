import { openDB } from 'idb';
import axios from 'axios';

const DB_NAME = 'macro-log-offline';
const STORE_NAME = 'meal-queue';
const DB_VERSION = 1;

// Initialize IndexedDB
async function getDB() {
    return openDB(DB_NAME, DB_VERSION, {
        upgrade(db) {
            if (!db.objectStoreNames.contains(STORE_NAME)) {
                db.createObjectStore(STORE_NAME, { keyPath: 'id', autoIncrement: true });
            }
        },
    });
}

// Add meal to offline queue
export async function queueMeal(mealData) {
    const db = await getDB();
    const tx = db.transaction(STORE_NAME, 'readwrite');
    const store = tx.objectStore(STORE_NAME);

    const queueItem = {
        ...mealData,
        timestamp: Date.now(),
        status: 'pending'
    };

    await store.add(queueItem);
    await tx.done;

    return queueItem;
}

// Get all queued meals
export async function getQueuedMeals() {
    const db = await getDB();
    return db.getAll(STORE_NAME);
}

// Remove meal from queue
export async function removeFromQueue(id) {
    const db = await getDB();
    const tx = db.transaction(STORE_NAME, 'readwrite');
    await tx.objectStore(STORE_NAME).delete(id);
    await tx.done;
}

// Sync queued meals
export async function syncQueuedMeals() {
    const queued = await getQueuedMeals();
    const results = [];

    for (const item of queued) {
        try {
            const response = await axios.post(route('meals.store'), {
                raw_input: item.raw_input,
            });

            await removeFromQueue(item.id);
            results.push({ success: true, item });
        } catch (error) {
            results.push({ success: false, item, error });
        }
    }

    return results;
}

// Get queue count
export async function getQueueCount() {
    const queued = await getQueuedMeals();
    return queued.length;
}

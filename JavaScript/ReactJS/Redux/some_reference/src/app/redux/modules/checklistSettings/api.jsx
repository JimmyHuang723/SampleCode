import axios from 'axios';

export function removeChecklistItemsWithSaga(ids) {
    return  axios.post('/assets/siud/d/setting/checklistItems.php', [{
        criteria:{
            member: WB.user.contact,
            ids: ids
        }
    }]);
}

export function removeChecklistSubTaskItemsWithSaga(ids) {
    return axios.post('/assets/siud/d/setting/checklistSubTaskItems.php', [{
        criteria:{
            member: WB.user.contact,
            ids: ids
        }
    }]);
}
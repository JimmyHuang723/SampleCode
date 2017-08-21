import CMAPopup from './modules/cmaPopup';
import ScrapPaper from './modules/scrapPaper';
import Cookings from './modules/cookings';
import FourQuadrants from './modules/fourQuadrants';
import AAABuyers from './modules/aaaBuyers';
import PendingListings from './modules/pendingListings';
import PendingSales from './modules/pendingSales';
import HotProperties from './modules/hotProperties';
import PointsGame from './modules/pointsGame';
import ForecastResults from './modules/forecastResults';
import DashboardResults from './modules/dashboardResults';
import HotPropertySelectionPopup from './modules/hotPropertySelectionPopup';
import ChecklistSettings from './modules/checklistSettings';
import ChecklistReport from './modules/checklistReport';

import { combineReducers } from 'redux';

const rootReducer = combineReducers({
    CMAPopup,
    ScrapPaper,
    Cookings,
    FourQuadrants,
    AAABuyers,
    PendingListings,
    PendingSales,
    HotProperties,
    PointsGame,
    ForecastResults,
    DashboardResults,
    HotPropertySelectionPopup,
    ChecklistSettings,
    ChecklistReport
});

export default rootReducer;
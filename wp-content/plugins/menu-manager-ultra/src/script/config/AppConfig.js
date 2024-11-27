
const proEnabled = process.env.PRO_ENABLED;

export const AppConfig = {

  appName: process.env.APP_NAME,
  debugNamespace: process.env.DEBUG,
  debugCategoryDefault: process.env.DEBUG_CATEGORY_DEFAULT,
  proEnabled: proEnabled && proEnabled.toLowerCase() == 'true' ? true : false,
  endpointBaseUrl: process.env.API_ENDPOINT_BASE_URL
} 
USE [EHSINFO]
GO

DECLARE	@return_value int

EXEC	@return_value = [dbo].[stf_observation_target_read]
		@param_filter_id = 21

SELECT	'Return Value' = @return_value

GO

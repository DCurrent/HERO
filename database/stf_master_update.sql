USE [EHSINFO]
GO

/****** Object:  StoredProcedure [dbo].[stf_master_update]    Script Date: 5/22/2017 10:25:33 AM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[stf_master_update]
	
	-- Parameters
	@param_id_list			xml			= NULL,		-- XML list of primary keys to update. 			
	@param_update_by		int			= NULL,		-- ID from account table.
	@param_update_host		varchar(50)	= NULL		-- User host, normally supplied from application.
			
AS
BEGIN

	SET NOCOUNT ON

	-- Validate arguments.
		IF @param_id_list IS NULL
			BEGIN
				-- Nothing to update, so exit. This can
				-- happen if procedure is being called as
				-- part of a sub record update when
				-- there aren't any sub records
				-- to update.
				RETURN
			END

		IF @param_update_by IS NULL
			BEGIN
				raiserror('Update By is NULL.', 9, 2)
				SET @param_update_by = -1
			END

		IF @param_update_host IS NULL
			BEGIN
				raiserror('Update Host is NULL.', 9, 2)
				SET @param_update_host = @@SERVERNAME
			END

	-- Let's create the temp tables we'll need.
		
		
		
		-- List of new item IDs created when
		-- inserts are perform on master
		-- table.
		CREATE TABLE #master_update_new_id_list
		(
			id_key			int
		)	
		

	-- Before we can do any work, we will
	-- need the list of group IDs for updating
	-- in table form. We'll get it here by
	-- parsing xml data.
		SELECT id
		INTO #master_update_request		
		FROM tvf_get_id_list(@param_id_list)	

	-- Find any record groups that match our 
	-- update list and mark the active
	-- versions as as inactive. 
	-- We also update the log updated fields 
	-- so we know the who/when of this 
	-- modification. 
		UPDATE
			tbl_stf_master
		SET
			active		= 0,
			update_time	= GETDATE(),	
			update_by	= @param_update_by,
			update_host	= @param_update_host
		FROM
			#master_update_request AS _request
		WHERE 
			tbl_stf_master.id = _request.id
			AND
			tbl_stf_master.active = 1;
	
	-- Apply the insert list (insert into master table). New
	-- IDs created by the database are output into
	-- a temp table.
		INSERT INTO 
			tbl_stf_master (id, 
							create_by,
							create_host, 
							update_time, 
							update_by, 
							update_host)
		OUTPUT 
			INSERTED.id_key 
				INTO #master_update_new_id_list
		
		SELECT 
			id, 
			@param_update_by,	
			@param_update_host,	
			GETDATE(),			
			@param_update_by,	
			@param_update_host
		FROM 
			#master_update_request

	-- For truly new records (that is, records that
	-- did not have a previous version record in Master
	-- Table), group IDs need to be seeded. We do that
	-- here by replacing the default value (-1) or any
	-- other non-ID value (0, NULL, etc.) with the
	-- primary key ID that was created by database.
		UPDATE
			tbl_stf_master
		SET
			id = _new.id_key
		FROM
			#master_update_new_id_list AS _new
		WHERE 
			tbl_stf_master.id_key = _new.id_key AND (tbl_stf_master.id IS NULL OR tbl_stf_master.id < 1); -- Any group ID that is NULL or accidently got a non ID value.

	-- Now let's output records that were updated. 
	-- into final result temp table. This allows 
	-- data update procedures to update the ID
	-- field in data tables with newly created 
	-- primary key ID based on the group ID.
			SELECT	ROW_NUMBER() OVER(ORDER BY _master.id_key) AS id_row,
					_master.id_key, 
					_master.id
			INTO #master_update_result
			FROM tbl_stf_master AS _master
				RIGHT JOIN #master_update_new_id_list _new ON _master.id_key = _new.id_key
		
		-- Output final result as a recordset.
		SELECT * FROM #master_update_result


END
GO


